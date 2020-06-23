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

require_once 'constants.php';

/**
 * Provides the appropriate menu sought by the request and some menus related functions
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class Menus implements \ArrayAccess
{
    protected $app;
    protected $menus = [];
    protected $menuAskUserBeforeReloadLastSession = [
        ASK_USER_BEFORE_RELOAD_LAST_SESSION => [
            'message' => 'Do you want to continue from where you left?',
            'actions' => [
                '1' => [
                    ITEM_MSG => 'Continue last session',
                    ITEM_ACTION => APP_CONTINUE_LAST_SESSION,
                ],
                '2' => [
                    ITEM_MSG => 'Restart',
                    ITEM_ACTION => APP_WELCOME,
                ],
            ],
        ],
    ];

    protected $menusPhp = '';
    protected $menusJson = '';

    public function __construct($app)
    {
        $this->app = $app;
        $this->session = $app->session();
        $this->menusPhp = $this->menuPath() . '/menus.php';
        $this->menusJson = $this->menuPath() . '/menus.json';
        $this->hydrateMenus($app);
    }

    public function menuPath()
    {
        return $this->app->config('menus_root_path') . '/' . $this->app->menusNamespace();
    }
    public function hydrateMenus($app)
    {
        $this->menus = $this->retrieveMenus();

        if ($this->session->hasMetadata(CURRENT_MENU_ACTIONS)) {
            $modifications = $this->session->metadata(CURRENT_MENU_ACTIONS)[ACTIONS];
            $this->insertMenuActions($modifications, $app->currentMenuName());
        }

        $this->menus = array_merge(
            $this->menus,
            $this->menuAskUserBeforeReloadLastSession
        );
    }

    public function modifyMenus($modifications)
    {
        foreach ($modifications as $menuName => $modif) {
            if (isset($modif[MSG])) {
                $this->menus[$menuName][MSG] = $modif[MSG];
            }

            if (isset($modif[ACTIONS])) {
                $this->insertMenuActions($modif[ACTIONS], $menuName);
            }
        }
    }

    public function insertMenuActions($actions, $menuName, $replace = false)
    {
        if (!isset($this->menus[$menuName][ACTIONS])) {
            $this->menus[$menuName][ACTIONS] = [];
        }

        if ($replace) {
            $this->menus[$menuName][ACTIONS] = $actions;
        } else {
            foreach ($actions as $key => $value) {
                $this->menus[$menuName][ACTIONS][$key] = $value;
            }
        }
    }

    public function setMenuActions($actions, $menuName)
    {
        $this->emptyActionsOfMenu($menuName);
        $this->insertMenuActions($actions, $menuName, true);
    }

    public function emptyActionsOfMenu($menuName)
    {
        $this->menus[$menuName][ACTIONS] = [];
    }

    public function retrieveMenus()
    {
        if (file_exists($this->menusPhp)) {
            return require $this->menusPhp;
        } elseif (file_exists($this->menusJson)) {
            return json_decode(
                file_get_contents($this->menusJson),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } elseif (class_exists($this->app->menuEntityClass('welcome'))) {
            return [];
        } else {
            throw new \Exception('Unable to found the Menus, neither in "' . $this->menusPhp . '", nor in "' . $this->menusJson . '".');
        }
    }

    public function getNextMenuName(
        $userResponse,
        $menuName,
        $userResponseExistsInMenuActions
    ) {
        if (!empty($forcedFlow = $this->session->metadata(FORCED_MENU_FLOW, []))) {
            $nextMenu = array_shift($forcedFlow);
            // var_dump($forcedFlow);
            $this->session->setMetadata(FORCED_MENU_FLOW, $forcedFlow);
            // var_dump($this->session->metadata(FORCED_MENU_FLOW, $forcedFlow));

            return $nextMenu;
        }

        if ($userResponseExistsInMenuActions) {
            if (is_array($this->menus[$menuName][ACTIONS][$userResponse][ITEM_ACTION])) {
                return $this->menus[$menuName][ACTIONS][$userResponse][ITEM_ACTION][MENU];
            }

            if (is_string($this->menus[$menuName][ACTIONS][$userResponse][ITEM_ACTION])) {
                return $this->menus[$menuName][ACTIONS][$userResponse][ITEM_ACTION];
            }

            throw new \Exception('Next menu name for option "' . $userResponse . '" in the menu "' . $menuName . '" must be an array or a string.');
        }

        if (
            $this->session->metadata('currentMenuSplitted', null) &&
            !$this->session->metadata('currentMenuSplitEnd', null) &&
            $userResponse === $this->app->params('splitted_menu_next_thrower')
        ) {
            return APP_SPLITTED_MENU_NEXT;
        }

        if (
            $this->session->metadata('currentMenuSplitted', null) &&
            !$this->session->metadata('currentMenuSplitStart', null) &&
            $userResponse === $this->app->params('back_action_thrower')
        ) {
            return APP_BACK;
        }

        if (isset($this->menus[$menuName][DEFAULT_MENU_ACTION])) {
            if (is_array($this->menus[$menuName][DEFAULT_MENU_ACTION])) {
                return $this->menus[$menuName][DEFAULT_MENU_ACTION][MENU];
            }

            if (is_string($this->menus[$menuName][DEFAULT_MENU_ACTION])) {
                return $this->menus[$menuName][DEFAULT_MENU_ACTION];
            }

            throw new \Exception('Default next menu name for the menu "' . $menuName . '" must be an array or a string.');
        }

        if ($nextMenu = $this->app->getNextMenuFromMenuEntity()) {
            if (is_array($nextMenu)) {
                return $nextMenu[MENU];
            }

            return $nextMenu;
        }

        return false;
    }

    public function forcedFlowIfExists($menuName, $response)
    {
        if (isset($this->menus[$menuName][ACTIONS][$response][ITEM_LATER])) {
            return $this->menus[$menuName][ACTIONS][$response][ITEM_LATER];
        }

        if (
            isset($this->menus[$menuName][ACTIONS][$response][ITEM_ACTION]) &&
            is_array($this->menus[$menuName][ACTIONS][$response][ITEM_ACTION]) &&
            isset($this->menus[$menuName][ACTIONS][$response][ITEM_ACTION][ITEM_LATER])
        ) {
            return $this->menus[$menuName][ACTIONS][$response][ITEM_ACTION][ITEM_LATER];
        }

        if (isset($this->menus[$menuName][ITEM_LATER])) {
            return $this->menus[$menuName][ITEM_LATER];
        }

        if (
            isset($this->menus[$menuName][DEFAULT_MENU_ACTION]) &&
            is_array($this->menus[$menuName][DEFAULT_MENU_ACTION]) &&
            isset($this->menus[$menuName][DEFAULT_MENU_ACTION][ITEM_LATER])
        ) {
            return $this->menus[$menuName][DEFAULT_MENU_ACTION][ITEM_LATER];
        }

        if ($nextMenu = $this->app->getNextMenuFromMenuEntity()) {
            if (is_array($nextMenu)) {
                return $nextMenu[ITEM_LATER];
            }
        }

        return null;
        // return (
        //     isset($this->menus[$menuName][ITEM_LATER]) ||
        //     isset($this->menus[$menuName][ACTIONS][$response][ITEM_LATER]) ||
        //     (isset($this->menus[$menuName][DEFAULT_MENU_ACTION]) &&
        //         is_array($this->menus[$menuName][DEFAULT_MENU_ACTION]) &&
        //         isset($this->menus[$menuName][DEFAULT_MENU_ACTION][ITEM_LATER]))
        // );
    }

    public function saveForcedFlow($actionLater/* , $menuName, $response */)
    {
        $type = gettype($actionLater);
        if (!in_array($type, ['array', 'string'])) {
            throw new \Exception('The parameter ' . ITEM_LATER . ' must be of  a the name or an array of the name(s) of the menu(s) you want to redirect to.');
        }

        $actionLater = is_array($actionLater) ? $actionLater : [$actionLater];
        $this->session->setMetadata(FORCED_MENU_FLOW, $actionLater);
    }

    public function menuStateExists($id)
    {
        return $id !== '' && (
            isset($this->menus[$id]) ||
            in_array($id, RESERVED_MENU_IDs, true) ||
            class_exists($this->app->menuEntityClass($id))
        );
    }

    public function splittedMenuNextActionDisplay()
    {
        return $this->app->params('splitted_menu_next_thrower') . ". " .
        $this->app->params('splitted_menu_display');
    }

    public function splittedMenuBackActionDisplay()
    {
        return $this->app->params('back_action_thrower') . ". " .
        $this->app->params('back_action_display');
    }

    public function getSplitMenuStringNext()
    {
        $index = $this->session->metadata('currentMenuSplitIndex') + 1;
        return $this->getSplitMenuStringAt($index);
    }

    public function getSplitMenuStringBack()
    {
        $index = $this->session->metadata('currentMenuSplitIndex') - 1;
        return $this->getSplitMenuStringAt($index);
    }

    public function getSplitMenuStringAt($index)
    {
        if ($index < 0) {
            throw new \Exception('Error: Splitted menu does not have page back page. This might not normally happen! Review the code.');
        } elseif (!isset($this->session->data['currentMenuChunks'][$index])) {
            throw new \Exception('Splitted menu does not have any next page.');
        }

        $this->updateSplittedMenuState($index);

        return $this->session->data['currentMenuChunks'][$index];
    }

    public function updateSplittedMenuState($index)
    {
        $end = count($this->session->data['currentMenuChunks']) - 1;

        switch ($index) {
            case 0:
                $this->session->data['currentMenuSplitStart'] = true;
                $this->session->data['currentMenuSplitEnd'] = false;
                break;

            case $end:
                $this->session->data['currentMenuSplitStart'] = false;
                $this->session->data['currentMenuSplitEnd'] = true;
                break;

            default:
                $this->session->data['currentMenuSplitStart'] = false;
                $this->session->data['currentMenuSplitEnd'] = false;
                break;
        }

        $this->session->data['currentMenuSplitIndex'] = $index;
    }

    public function getMenuString(
        $menuActions,
        $menu_msg = '',
        $hasBackAction = false
    ) {
        $menu_string = $this->menuToString($menuActions, $menu_msg);

        $chunks = explode("\n", $menu_string);
        $lines = count($chunks);

        if (
            strlen($menu_string) > $this->app->maxUssdPageContent() ||
            $lines > $this->app->maxUssdPageLines()
        ) {
            $menuChunks = $this->splitMenu($chunks, $hasBackAction);
            $menu_string = $menuChunks[0];

            $this->saveMenuSplittedState($menuChunks, $hasBackAction);
        } else {
            $this->unsetPreviousSplittedMenuIfExists();
        }

        return $menu_string;
    }

    public function splitMenu($menuStringChunks, $hasBackAction)
    {
        $menuChunks = [];

        $first = 0;
        $last = count($menuStringChunks) - 1;

        $currentStringWithoutSplitMenu = '';

        $splitted_menu_next = $this->splittedMenuNextActionDisplay();
        $splitted_menu_back = $this->splittedMenuBackActionDisplay();

        foreach (
            $menuStringChunks as $menu_item_number => $menu_item_str
        ) {
            $split_menu = '';

            if ($menu_item_number === $first || !isset($menuChunks[0])) {
                $split_menu = $splitted_menu_next;

                if ($hasBackAction) {
                    $split_menu .= "\n" . $splitted_menu_back;
                }
            } elseif ($menu_item_number === $last && !$hasBackAction) {
                $split_menu = $splitted_menu_back;
            } elseif ($menu_item_number !== $last) {
                $split_menu = $splitted_menu_next . "\n" . $splitted_menu_back;
            }

            $new_line = $menu_item_str;
            $new_line_with_split_menu = $menu_item_str . "\n" . $split_menu;
            if (
                strlen($new_line_with_split_menu) > $this->app->maxUssdPageContent() ||
                count(explode("\n", $new_line_with_split_menu)) > $this->app->maxUssdPageLines()
            ) {
                $max = $this->app->maxUssdPageContent() - strlen("\n" . $splitted_menu_next . "\n" . $splitted_menu_back);
                exit('The text <br>```<br>' . $menu_item_str . '<br>```<br><br> is too large to be displayed. Consider breaking it in pieces with the newline character (\n). Each piece must not exceed ' . $max . ' characters.');

                // $exploded = str_split($menu_item_str, $max);
                // $menu_item_str = join("\n", $exploded);
            }

            /*
             * The order is important here. (setting
             * current_string_with_split_menu before
             * currentStringWithoutSplitMenu)
             */
            $current_string_with_split_menu = $currentStringWithoutSplitMenu . "\n" . $new_line_with_split_menu;

            $currentStringWithoutSplitMenu .= "\n" . $new_line;

            $next = $menu_item_number + 1;
            $next_string_with_split_menu = '';

            if ($next < $last) {
                $next_line = "\n" . $menuStringChunks[$next];

                if (!isset($menuChunks[0])) {
                    $split_menu = "\n" . $splitted_menu_next;
                } else {
                    $split_menu = "\n" . $splitted_menu_next . "\n" . $splitted_menu_back;
                }

                $next_string_with_split_menu = $currentStringWithoutSplitMenu . $next_line . $split_menu;
            } else {
                $next_line = "\n" . $menuStringChunks[$last];
                $split_menu = $hasBackAction ? '' : "\n" . $splitted_menu_back;

                $next_string_with_split_menu = $currentStringWithoutSplitMenu . $next_line . $split_menu;
            }

            if (
                strlen($next_string_with_split_menu) >= $this->app->maxUssdPageContent() ||
                count(explode("\n", $next_string_with_split_menu)) >= $this->app->maxUssdPageLines() ||
                $menu_item_number === $last
            ) {
                $menuChunks[] = trim($current_string_with_split_menu);
                $current_string_with_split_menu = '';
                $currentStringWithoutSplitMenu = '';
            }
        }

        return $menuChunks;
    }

    public function saveMenuSplittedState($menuChunks, $hasBackAction)
    {
        $this->session->data['currentMenuSplitted'] = true;
        $this->session->data['currentMenuSplitIndex'] = 0;
        $this->session->data['currentMenuSplitStart'] = true;
        $this->session->data['currentMenuSplitEnd'] = false;
        $this->session->data['currentMenuChunks'] = $menuChunks;
        $this->session->data['currentMenuHasBackAction'] = $hasBackAction;
    }

    public function unsetPreviousSplittedMenuIfExists()
    {
        if (isset($this->session->data['currentMenuSplitted'])) {
            $this->session->data['currentMenuSplitted'] = false;

            $toDelete = [
                'currentMenuSplitIndex',
                'currentMenuSplitStart',
                'currentMenuSplitEnd',
                'currentMenuChunks',
                'currentMenuHasBackAction',
            ];

            foreach ($toDelete as $value) {
                unset($this->session->data[$value]);
            }
        }
    }

    public function menuToString($menuActions, $menu_msg = '')
    {
        $menu_string = $menu_msg . "\n\n";

        foreach ($menuActions as $menu_item_number => $menu_item_str) {
            $menu_string .= "$menu_item_number. $menu_item_str\n";
        }

        return trim($menu_string);
    }

    public function get($id)
    {
        if (!isset($this->menus[$id])) {
            throw new \Exception('Menu "' . $id . ' not found inside the menus.');
        }

        return $this->menus[$id];
    }

    public function has($id)
    {
        return isset($this->menus[$id]);
    }

    public function getAll()
    {
        return $this->menus;
    }

    // ArrayAccess Interface
    public function offsetExists($offset)
    {
        return isset($this->menus[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->menus[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->menus[] = $value;
        } else {
            $this->menus[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->menus[$offset]);
    }
}
