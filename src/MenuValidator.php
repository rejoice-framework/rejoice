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
 * Validate a menu flow.
 * Question on the usefulness of this class.
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class MenuValidator extends Validator
{

    public function checkMenu($jsonMenu)
    {
        $allMenus = json_decode($jsonMenu, true, 512, JSON_THROW_ON_ERROR);

        $result = ['SUCCESS' => true, 'response' => []];

        if (!isset($allMenus[WELCOME_MENU_NAME])) {
            $result['SUCCESS'] = false;
            $result['response'][WELCOME_MENU_NAME]['errors'] = "There must be a menu named " . WELCOME_MENU_NAME . " that will be the welcome menu of the application";
        }

        foreach ($allMenus as $menuName => $menu) {
            $infos = [];
            $errors = [];
            $warnings = [];

            if (!preg_match('/[a-z][a-z0-9_]+/i', $menuName) !== 1) {
                $errors['about_menu_name'] = $menuName . ' is an invalid menu name. Only letters, numbers and underscores are allowed.';
            }

            if (!isset($menu[MSG])) {
                $infos['about_message'] = "This menu does not have a message. It means will be generating a message from the 'before_" . $menuName . "' function in your application, unless you don't want anything to be displayed above your menu items.";
            } elseif (isset($menu[MSG]) && !is_string($menu[MSG])) {
                $errors['about_message'] = 'The message of this menu must be a string.';
            }

            $actionsErrors = [];

            if (!isset($menu[ACTIONS])) {
                $infos['about_actions'] = 'This menu does not have any following action. It will then be a final response.';
            } elseif (isset($menu[ACTIONS]) && !is_array($menu[ACTIONS])) {
                $actionsErrors = 'The actions of this menu must be an array.';
            } else {
                foreach ($menu[ACTIONS] as $key => $value) {
                    if (!preg_match('/[a-z0-9_]+/i', $key) !== 1) {
                        $actionsErrors[] = 'The key ' . $key . ' has an invalid format. Only letters, numbers and underscore are allowed.';
                    }

                    $nextMenu = '';

                    if (is_array($value)) {
                        $nextMenu = $value[ITEM_ACTION];
                    } elseif (is_string($value)) {
                        $nextMenu = $value;
                    }

                    if (
                        empty($nextMenu) ||
                        (!isset($allMenus[$nextMenu]) &&
                            !in_array($nextMenu, RESERVED_MENU_IDs, true))
                    ) {
                        $actionsErrors[$nextMenu] = 'The menu "' . $nextMenu . '" has been associated as following menu to this menu but it has not yet been implemented.';
                    }
                }
            }

            if (!empty($actionsErrors)) {
                $errors['about_actions'] = $actionsErrors;
            }

            if (!isset($menu[MSG]) && !isset($menu[ACTIONS])) {
                $warnings = "This menu does not have any message and any menu. Make sure you are returning a menu message in the 'before_" . $menuName . "' function.";
            }
            // END OF VERIFICATION

            if (!empty($errors) || !empty($warnings) || !empty($infos)) {
                $result['response'][$menuName] = [];
            }

            if (!empty($errors)) {
                $result['response']['SUCCESS'] = false;
                $result['response'][$menuName]['errors'] = $errors;
            }

            if (!empty($warnings)) {
                $result['response'][$menuName]['warnings'] = $warnings;
            }

            if (!empty($infos)) {
                $result['response'][$menuName]['infos'] = $infos;
            }
        }

        return $result;
    }
}
