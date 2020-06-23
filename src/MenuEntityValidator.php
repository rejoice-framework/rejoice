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
 * Validate a menu Entity.
 * Question on usefulness of this class.
 *
 * @author Prince Dorcis <princedorcis@gmail.com>
 */
class MenuEntityValidator extends Validator
{
    public function validateMenuEntity($menu_entity)
    {
        (!method_exists($menu_entity, 'params') or
            (method_exists($menu_entity, 'params') &&
                !is_array($menu_entity->params()))
        ) and
        exit('The menu manager object sent must have a "params" method that return an array containing parameters.');

        $params = $menu_entity->params();
        !isset($params['id']) and
        exit('The "params" must contain an "id" which value will be the id of the app.');

        isset($params['environment']) and
        !is_string($params['environment']) and
        exit("'environment' must be a string.");

        $this->validateStringParam($params['id'], 'id');

        isset($params['splitted_menu_display']) and
        !is_string($params['splitted_menu_display']) and
        exit("The parameter 'splitted_menu_display' must be a string.");

        isset($params['splitted_menu_next_thrower']) and
        !is_string($params['splitted_menu_next_thrower']) and
        exit("The parameter 'splitted_menu_next_thrower' must be a string.");

        isset($params['back_action_display']) and
        !is_string($params['back_action_display']) and
        exit("The parameter 'back_action_display' must be a string.");

        isset($params['back_action_thrower']) and
        !is_string($params['back_action_thrower']) and
        exit("The parameter 'back_action_thrower' must be a string.");

        isset($params['default_end_msg']) and
        !is_string($params['default_end_msg']) and
        exit("The parameter 'default_end_msg' must be a string.");

        isset($params['default_error_msg']) and
        !is_string($params['default_error_msg']) and
        exit("The parameter 'default_error_msg' must be a string.");

        isset($params['always_start_new_session']) and
        !is_bool($params['always_start_new_session']) and
        exit("The parameter 'always_start_new_session' must be a boolean.");

        isset($params['always_start_new_session']) and
        !is_bool($params['always_start_new_session']) and
        exit("The parameter 'always_start_new_session' must be a boolean.");

        isset($params['ask_user_before_reload_last_session']) and
        !is_bool($params['ask_user_before_reload_last_session']) and
        exit("The parameter 'ask_user_before_reload_last_session' must be a boolean.");

        isset($params['always_send_sms']) and
        !is_bool($params['always_send_sms']) and
        exit("The parameter 'always_send_sms' must be a boolean.");

        isset($params['sms_sender_name']) and
        $this->validateStringParam(
            $params['sms_sender_name'],
            'sms_sender_name',
            '/[a-z][a-z0-9+#$_@-]+/i',
            10
        );

        isset($params['sms_endpoint']) and
        !is_string($params['sms_endpoint']) and
        exit("The parameter 'sms_endpoint' must be a valid URL.");
    }
}
