<?php

/*
 * This file is part of the Rejoice package.
 *
 * (c) Prince Dorcis <princedorcis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Prinx\Rejoice;

require_once realpath(__DIR__) . '/../../../autoload.php';

require_once realpath(__DIR__) . '/../../dotenv/src/aliases_functions.php';
require_once 'constants.php';
require_once 'RequestValidator.php';
require_once 'FileSession.php';
require_once 'DatabaseSession.php';
require_once 'Menus.php';
require_once 'Database.php';
require_once 'UserResponse.php';
require_once 'Request.php';
require_once 'Response.php';
require_once 'SmsService.php';

// include_once realpath(__DIR__) . '/../../../../app/Helpers/helpers.php';

use App\Helpers\Log;
use Prinx\Rejoice\Config;
use Prinx\Utils\HTTP;
use Prinx\Utils\Str;
use Prinx\Utils\URL;

/**
 * Main Library. Handle the request and return a response.
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */

class Kernel
{
    protected $appName = 'default';
    protected $appDBs = [];
    protected $params = [];
    protected $session;
    protected $request;
    protected $validator;
    protected $menus;
    protected $logger;
    protected $currentMenuEntity = null;
    protected $nextMenuEntity = null;
    protected $customUssdRequestType;
    protected $nextMenuName = null;
    protected $currentMenuSplitted = false;
    protected $currentMenuSplitIndex = 0;
    protected $currentMenuSplitStart = false;
    protected $currentMenuSplitEnd = false;
    protected $currentMenuHasBackAction = false;
    protected $maxUssdPageContent = 147;
    protected $maxUssdPageLines = 10;
    protected $error = '';
    protected $sms = '';
    protected $endMethodAlreadyCalled = false;
    protected $appDbLoaded = false;
    protected $hasComeBack = false;

    public function __construct($appName)
    {
        $this->appName = $appName;
        $this->logger = new Log;
        $this->config = new Config;
        $this->request = new Request;
        $this->response = new Response($this);
        $this->validator = new RequestValidator($this);
    }

    public function run()
    {
        try {
            $this->loadAppParams();
            $this->validateRequest();
            $this->startSession();
            $this->loadMenus();
            $this->handleUserRequest();
        } catch (\Throwable $th) {
            $message = $th->getMessage() . "\nin file " . $th->getFile();
            $message .= ":" . $th->getLine();
            $this->logger->info($message);
            exit($message);
        }
    }

    public function validateRequest()
    {
        $this->validator->validate();
    }

    public function loadAppParams()
    {
        $params = require_once $this->config('app_config_path');
        $defaultParams = require_once 'appParams.php';
        $this->params = array_replace($defaultParams, $params);
    }

    public function startSession()
    {
        $sessionConfigPath = $this->config('session_config_path');
        $sessionDriver = (require $sessionConfigPath)['driver'];
        $session = $this->config('default_namespace') . ucfirst($sessionDriver) . 'Session';
        $this->session = new $session($this);
        // echo '<br>';
        // echo '<br>';
        // echo '<br>';
        // var_dump($this->session->data());

    }

    public function loadMenus()
    {
        $this->menus = new Menus($this);
    }

    protected function handleUserRequest()
    {
        if (
            $this->ussdRequestType() === APP_REQUEST_INIT &&
            $this->session->isPrevious()
        ) {
            $this->prepareToLaunchFromPreviousSessionState();
        }

        switch ($this->ussdRequestType()) {
            case APP_REQUEST_INIT:
                $this->runWelcomeState();
                break;

            case APP_REQUEST_USER_SENT_RESPONSE:
                if ($this->hasComeBackAfterNoTimeoutFinalResponse()) {
                    $this->allowTimeout();
                    $this->response->hardEnd();
                } elseif ($this->ussdHasSwitched()) {
                    $this->processFromRemoteUssd();
                } else {
                    $this->processResponse();
                }

                break;

            case APP_REQUEST_ASK_USER_BEFORE_RELOAD_LAST_SESSION:
                $this->runAskUserBeforeReloadLastSessionState();
                break;

            case APP_REQUEST_RELOAD_LAST_SESSION_DIRECTLY:
                $this->runLastSessionState();
                break;

            case APP_REQUEST_CANCELLED:
                // $this->session->delete();
                $this->response->hardEnd('REQUEST CANCELLED');
                break;

            default:
                $this->response->hardEnd('UNKNOWN USSD SERVICE OPERATOR');
                break;
        }
    }

    public function prepareToLaunchFromPreviousSessionState()
    {
        if (
            $this->params('ask_user_before_reload_last_session') &&
            !empty($this->session->metadata()) &&
            $this->session->metadata('current_menu_name') !== WELCOME_MENU_NAME
        ) {
            $this->setCustomUssdRequestType(APP_REQUEST_ASK_USER_BEFORE_RELOAD_LAST_SESSION);
        } else {
            $this->setCustomUssdRequestType(APP_REQUEST_RELOAD_LAST_SESSION_DIRECTLY);
        }
    }

    public function hasComeBackAfterNoTimeoutFinalResponse()
    {
        return $this->mustNotTimeout() && empty($this->session->metadata());
    }

    protected function runLastSessionState()
    {
        $this->setCurrentMenuName($menu = $this->backHistoryPop());
        $this->runState($this->currentMenuName());
    }

    public function currentMenuName()
    {
        return $this->session->metadata('current_menu_name', 'welcome');
    }

    protected function setCurrentMenuName($name)
    {
        $this->session->setMetadata('current_menu_name', $name);
    }

    protected function runAskUserBeforeReloadLastSessionState()
    {
        $this->runState(ASK_USER_BEFORE_RELOAD_LAST_SESSION);
    }

    protected function processResponse()
    {
        $response = $this->userResponse();

        /*
         * Do not use empty() to check the user response. The expected response
         * can for e.g. be 0 (zero), which empty() sees like empty.
         */
        if ($response === '') {
            $this->runInvalidInputState('Empty response not allowed');
            return;
        }

        $currentMenu = $this->currentMenuName();

        $this->loadMenuEntity($currentMenu, 'currentMenuEntity');

        $responseExistsInMenuActions =
        isset($this->menus[$currentMenu][ACTIONS][$response][ITEM_ACTION]);

        $nextMenu = $this->menus->getNextMenuName(
            $response,
            $currentMenu,
            $responseExistsInMenuActions
        );

        // echo $nextMenu;

        $userError = $nextMenu === false;

        $mustValidateResponse = $this->mustValidateResponse(
            $currentMenu,
            $userError,
            $responseExistsInMenuActions,
            $nextMenu
        );

        if ($mustValidateResponse) {
            $responseValid = $this->validateUserResponse(
                $response,
                $currentMenu,
                $nextMenu
            );

            $userError = !$responseValid;
        }

        if ($userError) {
            if ($this->params('end_on_user_error')) {
                $this->response->hardEnd($this->default_error_msg);
            } else {
                $this->runInvalidInputState();
            }

            return;
        }

        if (!$this->menus->menuStateExists($nextMenu)) {
            $this->response->addWarningInSimulator(
                'The next menu `' . $nextMenu . '` cannot be found.'
            );

            if ($this->params('end_on_unhandled_action')) {
                $this->response->hardEnd('Action not handled.');
            } else {
                $this->runInvalidInputState('Action not handled.');
            }

            return;
        }

        if (
            $actionLater = $this->menus->forcedFlowIfExists($currentMenu, $response)
        ) {
            // $this->menus->saveForcedFlow($actionLater/* , $currentMenu, $response */);
            $this->menus->saveForcedFlow($actionLater);
        }

        $this->setNextMenuName($nextMenu);

        $this->saveUserResponse($response);

        $this->callAfterMenuHook($response, $currentMenu, $nextMenu);

        // Todo: search for a proper way of determining if moving to next menu
        if (
            $nextMenu === APP_END ||
            $nextMenu === APP_WELCOME ||
            !in_array($nextMenu, RESERVED_MENU_IDs)
        ) {
            $this->callOnMoveToNextMenuHook($response, $currentMenu, $nextMenu);
        }

        if (URL::isUrl($nextMenu)) {
            $this->callOnMoveToNextMenuHook($response, $currentMenu, $nextMenu);
            return $this->switchToRemoteUssd($nextMenu);
        }

        return $this->runAppropriateState($nextMenu);
    }

    public function mustValidateResponse($currentMenu, $userError, $responseExistsInMenuActions, $nextMenu)
    {
        return (
            (isset($this->menus[$currentMenu][DEFAULT_MENU_ACTION]) /* ||
        isset($this->menus[$currentMenu][ITEM_LATER]) */) &&
            !$userError &&
            !$responseExistsInMenuActions &&
            !in_array($nextMenu, RESERVED_MENU_IDs)
        );
    }

    /**
     * Load Menu Entity
     *
     * @param string $class
     * @param string $type ('current'|'next')
     * @return void
     */
    public function loadMenuEntity($menuName, $entityType)
    {
        $menuEntityClass = $this->menuEntityClass($menuName);

        if (!$this->$entityType && class_exists($menuEntityClass)) {
            $this->$entityType = new $menuEntityClass($menuName);
            $this->$entityType->setApp($this);
        }
    }

    protected function runAppropriateState($nextMenu)
    {
        switch ($nextMenu) {
            case APP_BACK:
                $this->runPreviousState();
                break;

            case APP_SPLITTED_MENU_NEXT:
                $this->runSameStateNextPage();
                break;

            case APP_END:
                $this->response->hardEnd();
                break;

            case APP_WELCOME:
                $this->runWelcomeState();
                break;

            case APP_SAME:
                $this->runSameState();
                break;

            case APP_CONTINUE_LAST_SESSION:
                // $this->setCurrentMenuName($menu = $this->backHistoryPop());
                // var_dump($menu);
                $this->runLastSessionState();
                break;

            case APP_PAGINATE_FORWARD:
                $this->runPaginateForwardState();
                break;

            case APP_PAGINATE_BACK:
                $this->runPaginateBackState();
                break;

            default:
                $this->runState($nextMenu);
                break;
        }
    }

    protected function switchToRemoteUssd($nextMenu)
    {
        $this->session->setMetadata('switched_ussd_endpoint', $nextMenu);
        $this->session->setMetadata('ussd_has_switched', true);
        $this->session->save();

        $this->setUssdRequestType(APP_REQUEST_INIT);

        return $this->processFromRemoteUssd($nextMenu);
    }

    protected function saveUserResponse($userResponse)
    {
        $toSave = $userResponse;
        $alreadyGotSaveAsResponse = false;

        $method = MENU_ENTITY_SAVE_RESPONSE_AS;

        if (
            // !(
            //     $userResponseExistsInMenuActions &&
            //     in_array($nextMenuName, RESERVED_MENU_IDs, true)
            // ) &&
            $this->currentMenuEntity &&
            method_exists($this->currentMenuEntity, $method)
        ) {
            $toSave = call_user_func(
                [$this->currentMenuEntity, $method],
                $toSave, $this->userPreviousResponses()
            );

            if ($toSave === null) {
                $this->response->addWarningInSimulator('The method `' . $method .
                    '` in the class ' . $this->currentMenuEntity . ' returns `NULL`.
                 That may means the method does not return anything or you are
                  deliberately returning NULL. <strong>NULL will be saved as
                  the user\'s response</strong>! Check that method (' .
                    $this->currentMenuEntity . '::' . $method . ') if you think it
                  must return something else.'
                );
            }

            $alreadyGotSaveAsResponse = true;
        }

        $name = $this->currentMenuName();

        if (
            isset($this->menus[$name][ACTIONS][$userResponse][SAVE_RESPONSE_AS])
        ) {
            if (!$alreadyGotSaveAsResponse) {
                $toSave = $this->menus[$name]
                    [ACTIONS]
                    [$userResponse]
                    [SAVE_RESPONSE_AS];
                $alreadyGotSaveAsResponse = true;
            } else {
                $this->response->addWarningInSimulator('There is a `' . $method .
                    '` method in the class ' . $this->currentMenuEntity . ' while this menu (' . $name . ') contains a `' . SAVE_RESPONSE_AS . '` attribute. The `' . $method .
                    '` method has precedence on the menu attribute. Its return value will be used as the user\'s response instead of the `' . SAVE_RESPONSE_AS . '` attribute.'
                );
            }
        }

        $this->userPreviousResponsesAdd($toSave);
    }

    public function validateFromMenu($menuName, $response)
    {
        if (!isset($this->menus[$menuName][VALIDATE])) {
            return true;
        }

        $rules = $this->menus[$menuName][VALIDATE];

        $validation = UserResponseValidator::validate($response, $rules);

        if (!$validation->validated) {
            $this->error .= "\n" . $validation->error;
        }

        return $validation->validated;
    }

    public function validateFromMenuEntity($nextMenuName, $response)
    {
        $validateMethod = MENU_ENTITY_VALIDATE_RESPONSE;

        if (
            !in_array($nextMenuName, RESERVED_MENU_IDs, true) &&
            $this->currentMenuEntity &&
            method_exists($this->currentMenuEntity, $validateMethod)
        ) {

            $validated = call_user_func(
                [$this->currentMenuEntity, $validateMethod],
                $response, $this->userPreviousResponses()
            );

            if (!is_bool($validated)) {
                throw new \Exception('The method `' . $validateMethod . '` inside `' . $this->currentMenuEntity . '` class must return a boolean.');
            }

            return $validated;
        }

        return true;
    }

    protected function validateUserResponse(
        $response,
        $menuName,
        $nextMenuName
    ) {
        $validated = $this->validateFromMenu($menuName, $response);

        return $validated ? $this->validateFromMenuEntity($nextMenuName, $response) : $validated;
    }

    public function getNextMenuFromMenuEntity()
    {
        $defaultNextMethod = MENU_ENTITY_DEFAULT_NEXT_MENU;

        if (
            $this->currentMenuEntity &&
            method_exists($this->currentMenuEntity, $defaultNextMethod)
        ) {
            return call_user_func([$this->currentMenuEntity, $defaultNextMethod]);
        }

        return false;
    }

    protected function callAfterMenuHook(
        $userResponse,
        $menuName,
        $nextMenuName
    ) {
        $afterMethod = MENU_ENTITY_AFTER;

        if (
            $this->currentMenuEntity &&
            method_exists($this->currentMenuEntity, $afterMethod)
        ) {
            call_user_func(
                [$this->currentMenuEntity, $afterMethod],
                $userResponse, $this->userPreviousResponses()
            );
        }
    }

    protected function callOnMoveToNextMenuHook(
        $userResponse,
        $menuName,
        $nextMenuName
    ) {
        $onMoveToNextMenuMethod = MENU_ENTITY_ON_MOVE_TO_NEXT_MENU;

        if (
            $this->currentMenuEntity &&
            method_exists($this->currentMenuEntity, $onMoveToNextMenuMethod)
        ) {
            call_user_func(
                [$this->currentMenuEntity, $onMoveToNextMenuMethod],
                $userResponse, $this->userPreviousResponses()
            );
        }
    }

    protected function processFromRemoteUssd($endpoint = '')
    {
        $endpoint = $endpoint ?: $this->switchedUssdEndpoint();

        $response = HTTP::post($this->request->input(), $endpoint);

        if ($response['SUCCESS']) {
            $this->response->sendRemote($response['data']);
        } else {
            $this->response->sendRemote($response['error']);
        }
    }

    public function switchedUssdEndpoint()
    {
        return $this->session->metadata('switched_ussd_endpoint', '');
    }

    public function ussdHasSwitched()
    {
        return $this->session->metadata('ussd_has_switched', false);
    }

    protected function runSameStateNextPage()
    {
        $this->userPreviousResponsesPop($this->currentMenuName());
        $this->runNextState(APP_SPLITTED_MENU_NEXT);
    }

    protected function getErrorIfExists($menuName)
    {
        if ($this->error() && $menuName === $this->currentMenuName()) {
            return $this->error();
        }

        return '';
    }

    protected function callOnBackHook()
    {
        $this->loadMenuEntity($this->currentMenuName(), 'currentMenuEntity');

        if (method_exists($this->currentMenuEntity, MENU_ENTITY_ON_BACK)) {
            call_user_func(
                [$this->currentMenuEntity, MENU_ENTITY_ON_BACK],
                $this->userPreviousResponses()
            );
        }
    }

    protected function callFeedMenuMessageHook($menuName)
    {
        $resultCallBefore = '';

        $callBefore = MENU_ENTITY_MESSAGE;

        if (
            $this->nextMenuEntity &&
            method_exists($this->nextMenuEntity, $callBefore)
        ) {
            $resultCallBefore = call_user_func(
                [$this->nextMenuEntity, $callBefore],
                $this->userPreviousResponses()
            );
        }

        if (isset($this->menus[$menuName][MSG])) {
            if (
                !is_string($resultCallBefore) &&
                !is_array($resultCallBefore)
            ) {
                throw new \Exception("STRING OR ARRAY EXPECTED.\nThe function '" . $callBefore . "' in class '" . $this->nextMenuEntity . "' must return either a string or an associative array. If it returns a string, the string will be appended to the message of the menu. If it return an array, the library will parse the menu message and replace all words that are in the form :indexofthearray: by the value associated in the array. Check the documentation to learn more on how to use the '" . $callBefore . "' functions.");
            }
        } else {
            if (!is_string($resultCallBefore)) {
                throw new \Exception("STRING EXPECTED.\nThe function '" . $callBefore . "' in class '" . $this->nextMenuEntity . "' must return a string if the menu itself does not have any message. Check the documentation to learn more on how to use the '" . $callBefore . "' functions.");
            }
        }

        return $resultCallBefore;
    }

    protected function callFeedActionsHook($menuName)
    {
        $actionHookResult = [];
        $actionHook = MENU_ENTITY_ACTIONS;

        if (
            $this->nextMenuEntity &&
            method_exists($this->nextMenuEntity, $actionHook)
        ) {
            $actionHookResult = call_user_func(
                [$this->nextMenuEntity, $actionHook],
                $this->userPreviousResponses()
            );
        }

        if (!is_array($actionHookResult)) {
            throw new \Exception("ARRAY EXPECTED.\nThe method '" . $actionHook . "' in class '" . $this->nextMenuEntity . "' must return an associative array.");
        }

        return $actionHookResult;
    }

    protected function callBeforeHook($menuName)
    {
        $callBefore = MENU_ENTITY_BEFORE;

        if (
            $this->nextMenuEntity &&
            method_exists($this->nextMenuEntity, $callBefore)
        ) {
            call_user_func(
                [$this->nextMenuEntity, $callBefore],
                $this->userPreviousResponses()
            );
        }
    }

    protected function runState($nextMenuName)
    {
        $this->loadMenuEntity($nextMenuName, 'nextMenuEntity');

        $this->callBeforeHook($nextMenuName);

        // The softEnd or harEnd method can be called inside
        // the `before` method. If so, we terminate the script here
        if ($this->endMethodAlreadyCalled) {
            $this->session->hardReset();
            exit;
        }

        $message = $this->currentMenuMessage($nextMenuName);

        $actions = $this->currentMenuActions($nextMenuName);

        if ($this->isLastMenuPage($actions)) {
            $isUssdChannel = $this->isUssdChannel();

            /* We save message for SMS before we add any "Cancel" message,
             * which is only let the user cancel the ussd prompt
             */
            $this->sms = $message;

            if (
                $isUssdChannel &&
                $this->mustNotTimeout() &&
                $this->params('cancel_msg')
            ) {
                $message .= "\n\n" . $this->params('cancel_msg');
            }

            if (
                !$isUssdChannel ||
                ($isUssdChannel &&
                    !$this->contentOverflows($message))
            ) {
                $this->runLastState($message);
                return;
            }
        }

        $this->runNextState(
            $nextMenuName,
            $message,
            $actions,
            $this->currentMenuHasBackAction
        );
    }

    public function isUssdChannel()
    {
        return $this->channel() === 'USSD';
    }

    public function mustNotTimeout()
    {
        return $this->params('allow_timeout') === false;
    }

    public function allowTimeout()
    {
        $this->setParam('allow_timeout', true);
    }

    public function contentOverflows($message)
    {
        return strlen($message) > $this->maxUssdPageContent || count(explode("\n", $message)) > $this->maxUssdPageLines;
    }

    protected function isLastMenuPage($actions)
    {
        return empty($actions);
        // return !isset($this->menus[$menuName][ACTIONS]);
    }

    protected function currentMenuMessage($nextMenuName)
    {
        $message = $this->getErrorIfExists($nextMenuName);
        $resultCallBefore = $this->callFeedMenuMessageHook($nextMenuName);

        if (isset($this->menus[$nextMenuName][MSG])) {

            $menu_msg = $this->menus[$nextMenuName][MSG];

            if (is_string($resultCallBefore)) {
                if (empty($menu_msg)) {
                    $menu_msg = $resultCallBefore;
                } else {
                    $menu_msg = $resultCallBefore ? $resultCallBefore . "\n" . $menu_msg : $menu_msg;
                }
            } elseif (is_array($resultCallBefore)) {
                foreach ($resultCallBefore as $pattern_name => $value) {
                    $pattern = '/' . MENU_MSG_PLACEHOLDER . $pattern_name . MENU_MSG_PLACEHOLDER . '/';
                    $menu_msg = preg_replace($pattern, $value, $menu_msg);
                }
            }

            $message .= $message ? "\n" . $menu_msg : $menu_msg;
        } else {
            if (empty($message)) {
                $message = $resultCallBefore;
            } else {
                $message = $resultCallBefore ? $resultCallBefore . "\n" . $message : $message;
            }
        }

        return $message;
    }

    protected function currentMenuActions($nextMenuName)
    {
        $this->currentMenuHasBackAction = false;

        $actionsFromMenuFlow = $this->menus[$nextMenuName][ACTIONS] ?? [];
        $actionsFromMenuEntity = $this->callFeedActionsHook($nextMenuName);

        // print_r($nextMenuName);
        // print_r($actionsFromMenuEntity);
        // print_r($actionsFromMenuFlow);
        // print_r($this->menus[$nextMenuName]);
        // exit;

        $actions = array_replace(
            $actionsFromMenuFlow,
            $actionsFromMenuEntity
        );

        $toDisplay = [];
        $toSave = [];

        foreach ($actions as $index => $value) {
            // To be reviewed
            if (
                $index == '0' ||
                array_search($index, RESERVED_MENU_ACTIONS) === false
            ) {
                $toDisplay[$index] = $value[ITEM_MSG];
                $toSave[$index] = $value;

                if (
                    !$this->currentMenuHasBackAction &&
                    isset($value[ITEM_ACTION]) &&
                    $value[ITEM_ACTION] === APP_BACK ||
                    $value[ITEM_ACTION] === APP_PAGINATE_BACK
                ) {
                    $this->currentMenuHasBackAction = true;
                }
            }
        }

        $this->persistMenuActions($toSave, $nextMenuName);
        return $toDisplay;
    }

    protected function persistMenuActions($actions, $nextMenuName)
    {
        $this->setMenuActions($actions, $nextMenuName);
    }

    protected function runWelcomeState()
    {
        if (!$this->menus->has(WELCOME_MENU_NAME)) {
            throw new \Exception('No welcome menu defined. There must be at least one menu named `welcome` which will be the first displayed menu.');
        }

        $this->session->reset();
        $this->runState(WELCOME_MENU_NAME);
    }

    protected function runNextState(
        $nextMenuName,
        $message = '',
        $menuActions = [],
        $hasBackAction = false
    ) {
        $menuString = '';

        if ($nextMenuName === APP_SPLITTED_MENU_NEXT) {
            $menuString = $this->menus->getSplitMenuStringNext();
            $hasBackAction = $this->session
                ->metadata('currentMenuHasBackAction');
        } elseif ($nextMenuName === APP_SPLITTED_MENU_BACK) {
            $menuString = $this->menus->getSplitMenuStringBack();
            $hasBackAction = $this->session
                ->metadata('currentMenuHasBackAction');
        } else {
            $menuString = $this->menus->getMenuString(
                $menuActions,
                $message,
                $hasBackAction
            );
        }

        if ($this->params('environment') !== DEV) {
            $this->response->send($menuString);
        }

        // $this->send->response($menuString);

        if (
            $nextMenuName !== APP_SPLITTED_MENU_NEXT &&
            $nextMenuName !== APP_SPLITTED_MENU_BACK
        ) {
            if (
                $this->currentMenuName() &&
                $this->currentMenuName() !== WELCOME_MENU_NAME &&
                $nextMenuName !== ASK_USER_BEFORE_RELOAD_LAST_SESSION &&
                !empty($this->backHistory()) &&
                $nextMenuName === $this->previousMenuName()
            ) {
                $this->backHistoryPop();
            } elseif (
                $this->currentMenuName() &&
                $nextMenuName !== $this->currentMenuName() &&
                $this->currentMenuName() !== ASK_USER_BEFORE_RELOAD_LAST_SESSION
            ) {
                $this->backHistoryPush($this->currentMenuName());
            }

            $this->setCurrentMenuName($nextMenuName);
        }

        $this->session->save();
        // In development mode send the response only after everything has been done
        if ($this->params('environment') === DEV) {
            $this->response->send($menuString);
        }
    }

    public function runLastState($message = '')
    {
        $message = trim($message);

        /*
         * In production, for timeout reason, push immediately the response
         * before doing any other thing, especially calling an API, like
         * sending a message, which may take time.
         */
        if ($this->params('environment') !== DEV) {
            $this->response->softEnd($message);
        }

        if ($message && $this->params('always_send_sms_at_end')) {
            $sms = $this->sms ? $this->sms : $message;
            $this->sendSms($sms);
        }

        /*
         * In development, pushing the response to the user will rather be
         * the last thing, to be able to receive any ever error, warning or
         * info in the simulator.
         */
        if ($this->params('environment') === DEV) {
            $this->response->softEnd($message);
        }

        $this->session->hardReset();
    }

    protected function runPreviousState()
    {
        $this->hasComeBack = true;
        $this->userPreviousResponsesPop($this->currentMenuName());

        if (
            $this->session->hasMetadata('currentMenuSplitted') &&
            $this->session->metadata('currentMenuSplitted') &&
            $this->session->hasMetadata('currentMenuSplitIndex') &&
            $this->session->metadata('currentMenuSplitIndex') > 0
        ) {
            $this->runNextState(APP_SPLITTED_MENU_BACK);
        } else {
            $this->callOnBackHook();
            $previousMenuName = $this->previousMenuName();
            $this->userPreviousResponsesPop($previousMenuName);
            $this->runState($previousMenuName);
        }
    }

    protected function userPreviousResponsesPop($menuName)
    {
        if ($this->userPreviousResponses()) {
            if (
                isset($this->session->data['user_previous_responses'][$menuName]) &&
                is_array($this->session->data['user_previous_responses'][$menuName])
            ) {
                return array_pop($this->session->data['user_previous_responses'][$menuName]);
            }
        }

        return null;
    }

    protected function userPreviousResponsesAdd($response)
    {
        $id = $this->currentMenuName();

        if (
            !isset($this->userPreviousResponses()[$id]) ||
            !is_array($this->userPreviousResponses()[$id])
        ) {
            $this->session->data['user_previous_responses'][$id] = [];
        }

        $this->session->data['user_previous_responses'][$id][] = $response;
    }

    public function userPreviousResponses($menuName = null)
    {
        if (!$this->session->hasMetadata('user_previous_responses')) {
            $this->session->setMetadata('user_previous_responses', []);
        }

        $previousSavedResponses = $this->session
            ->metadata('user_previous_responses');

        $responses = new UserResponse($previousSavedResponses);

        return $menuName ? $responses[$menuName] : $responses;
    }

    protected function runSameState()
    {
        $this->userPreviousResponsesPop($this->currentMenuName());
        $this->runState($this->currentMenuName());
    }

    protected function callOnPaginateForwardHook()
    {
        $this->callOnPaginateHook(MENU_ENTITY_ON_PAGINATE_FORWARD);
    }

    protected function callOnPaginateBackHook()
    {
        $this->callOnPaginateHook(MENU_ENTITY_ON_PAGINATE_BACK);
    }

    protected function callOnPaginateHook($hook)
    {
        $this->loadMenuEntity($this->currentMenuName(), 'currentMenuEntity');

        if (method_exists($this->currentMenuEntity, $hook)) {
            call_user_func(
                [$this->currentMenuEntity, $hook],
                $this->userPreviousResponses()
            );
        }
    }

    public function runPaginateForwardState()
    {
        $this->callOnPaginateForwardHook();
        $this->runSameState();
    }

    public function runPaginateBackState()
    {
        $this->callOnPaginateBackHook();
        $this->runSameState();
    }

    protected function runInvalidInputState($error = '')
    {
        if ($error) {
            $this->setError($error);
        } else {
            $error = empty($this->error()) ? $this->params('default_error_msg') : $this->error();
            $this->setError($error);
        }

        $this->runState($this->currentMenuName());
    }

    /**
     * The formatter is removing the public|protected when the method name
     * is 'exit'
     * TO BE REVIEWED.
     */
    function exit($message = '') {
        $this->response->hardEnd($message);
    }

    public function insertMenuActions($actions, $replace = false, $menuName = '')
    {
        $menuName = $menuName ?: $this->nextMenuName();
        // echo $menuName;
        $menuName = $menuName ?: $this->currentMenuName();
        if (!$this->session->hasMetadata(CURRENT_MENU_ACTIONS)) {
            $this->session->setMetadata(CURRENT_MENU_ACTIONS, [ACTIONS => []]);
        }

        if ($replace) {
            $allActions = $this->session->metadata(CURRENT_MENU_ACTIONS);
            $allActions[ACTIONS] = $actions;
            $this->session->setMetadata(CURRENT_MENU_ACTIONS, $allActions);
        } else {
            foreach ($actions as $key => $value) {
                $this->session->data[CURRENT_MENU_ACTIONS][ACTIONS][$key] = $value;
            }
        }

        return $this->menus->insertMenuActions($actions, $menuName, $replace);
    }

    public function emptyActionsOfMenu($menuName)
    {
        $this->session->removeMetadata(CURRENT_MENU_ACTIONS);
        $this->menus->emptyActionsOfMenu($menuName);
    }

    public function setMenuActions($actions, $menuName)
    {
        $this->emptyActionsOfMenu($menuName);
        $this->insertMenuActions($actions, $menuName);
    }

    protected function setNextMenuName($id)
    {
        $this->nextMenuName = $id;
    }

    public function nextMenuName()
    {
        return $this->nextMenuName;
    }

    public function previousMenuName()
    {
        $length = count($this->backHistory());

        if (!$length) {
            throw new \Exception("Can't get a previous menu. 'back_history' is empty.");
        }

        return $this->backHistory()[$length - 1];
    }

    /**
     * Allow developer to save a value in the session
     */
    public function sessionSave($name, $value)
    {
        $this->session->set($name, $value);
    }

    /**
     * Allow developer to retrieve information (s)he saved in the session.
     */
    public function sessionGet($name, $default = null)
    {
        return $this->session($name, $default);
    }

    /**
     * Allow developer to check if the session contains an index.
     */
    public function sessionHas($name)
    {
        return $this->session->has($name);
    }

    public function sessionRemove($name)
    {
        $this->session->remove($name);
    }

    public function session($key = null, $default = null)
    {
        // echo 'CALLED';
        // echo '<br>';
        if (!$key) {
            return $this->session;
        }

        return $this->session->get($key, $default);
    }

    public function backHistory()
    {
        if (!$this->session->hasMetadata('back_history')) {
            $this->session->setMetadata('back_history', []);
        }

        return $this->session->metadata('back_history');
    }

    protected function backHistoryPush($menuName)
    {
        $history = $this->backHistory();
        array_push($history, $menuName);
        $this->session->setMetadata('back_history', $history);
    }

    protected function backHistoryPop()
    {
        $history = $this->backHistory();
        $lastMenu = array_pop($history);
        $this->session->setMetadata('back_history', $history);
        return $lastMenu;
    }

    public function loadAppDBs()
    {
        $this->appDBs = Database::loadAppDBs();
        $this->appDbLoaded = true;
    }

    public function db($id = '')
    {
        if ($this->params('connect_app_db')) {
            $id = $id === '' ? 'default' : $id;

            if (!$this->appDbLoaded) {
                $this->loadAppDBs();
            }

            if ($id === 'default' && !isset($this->appDBs['default'])) {
                throw new \Exception('No default database set! Kindly update your database configurations in "config/database.php". <br/> At least one database has to have the index "default" in the array return in "config/database.php". If not, you will need to specify the name of the database you want to load.');
            } elseif (!isset($this->appDBs[$id])) {
                throw new \Exception('No database configuration set with the name "' . $id . '" in "config/database.php"!');
            }

            return $this->appDBs[$id];
        } else {
            throw new \Exception('Database not connected. Please set "connect_app_db" to boolean `true` in the "config/app.php" to enable connection to the database.');
        }
    }

    public function maxUssdPageContent()
    {
        return $this->maxUssdPageContent;
    }

    public function maxUssdPageLines()
    {
        return $this->maxUssdPageLines;
    }

    public function hasComeBack()
    {
        return $this->hasComeBack;
    }

    public function createAppNamespace($prefix = '')
    {
        $namespace = Str::pascalCase($this->appName);

        $pos = strpos(
            $namespace,
            $prefix,
            strlen($namespace) - strlen($prefix)
        );

        $not_already_prefixed = $pos === -1 || $pos !== 0;

        if ($not_already_prefixed) {
            $namespace .= $prefix;
        }

        return $namespace;
    }

    public function setEndMethodAlreadyCalled($methodCalled)
    {
        $this->endMethodAlreadyCalled = $methodCalled;
    }

    public function menusNamespace()
    {
        return $this->createAppNamespace(MENUS_NAMESPACE_PREFIX);
    }

    public function menuEntitiesNamespace()
    {
        return $this->createAppNamespace(MENU_ENTITIES_NAMESPACE_PREFIX);
    }

    public function menuEntityNamespace($menuName)
    {
        return Str::pascalCase($menuName);
    }

    public function menuEntityClass($menuName)
    {
        return MENU_ENTITIES_NAMESPACE .
        $this->menuEntitiesNamespace() . '\\' .
        $this->menuEntityNamespace($menuName);
    }

    public function currentMenuEntity()
    {
        return $this->currentMenuEntity;
    }

    public function nextMenuEntity()
    {
        return $this->nextMenuEntity;
    }

    public function id()
    {
        return $this->params('id');
    }

    public function setParam($name, $value)
    {
        return $this->params[$name] = $value;
    }

    public function error()
    {
        return $this->error;
    }

    public function msisdn()
    {
        return $this->request->input('msisdn');
    }

    public function network()
    {
        return $this->request->input('network');
    }

    public function sessionId()
    {
        return $this->request->input('sessionID');
    }

    public function userResponse()
    {
        return $this->request->input('ussdString');
    }

    public function channel()
    {
        return $this->request->input('channel');
    }

    public function ussdRequestType()
    {
        if ($this->customUssdRequestType !== null) {
            return $this->customUssdRequestType;
        }

        return $this->request->input('ussdServiceOp');
    }

    public function setError(string $error = '')
    {
        $this->error = $error;
        return $this;
    }

    public function params($name)
    {
        return $this->params[$name];
    }

    public function request($name = null)
    {
        if (!$name) {
            return $this->request;
        }

        return $this->request->input($name);
    }

    public function response()
    {
        return $this->response;
    }

    protected function setUssdRequestType($requestType)
    {
        $possibleTypes = [
            APP_REQUEST_INIT,
            APP_REQUEST_END,
            APP_REQUEST_CANCELLED,
            APP_REQUEST_ASK_USER_RESPONSE,
            APP_REQUEST_USER_SENT_RESPONSE,
        ];

        if (!in_array($requestType, $possibleTypes)) {
            $message = 'Trying to set a request type but the value provided "' . $requestType . '" is invalid.';
            throw new \Exception($message);
        }

        $this->request->forceInput('ussdServiceOp', $requestType);

        return $this;
    }

    protected function setCustomUssdRequestType($requestType)
    {
        $this->customUssdRequestType = $requestType;
    }

    public function sendSms($message, $msisdn = '', $senderName = '', $endpoint = '')
    {
        $smsService = new SmsService($this);

        return $smsService->send($message, $msisdn, $senderName, $endpoint);
    }

    public function config($name = null)
    {
        if (!$name) {
            return $this->config;
        } elseif ($this->config->has($name)) {
            return $this->config->get($name);
        }

        throw new \Exception('Key `' . $name . '` not found in the config');
    }

    public function logger()
    {
        return $this->logger;
    }
}
