<?php

/*
 * This file is part of the Rejoice package.
 *
 * (c) Prince Dorcis <princedorcis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once 'constants.php';

return [
    'id' => '',
    'environment' => DEV,
    'back_action_thrower' => '0',
    'back_action_display' => 'Back',
    'splitted_menu_next_thrower' => '00',
    'splitted_menu_display' => 'More',
    'default_end_msg' => 'Thank you!',
    'end_on_user_error' => false,
    'end_on_unhandled_action' => false,
    'validate_shortcode' => false,
    'connect_app_db' => false,

    /*
     * Use by the Session instance to know if it must start a new
     * session or use the user previous session, if any.
     */
    'always_start_new_session' => true,

    /*
     * This property has no effect when "always_start_new_session"
     * is false
     */
    'ask_user_before_reload_last_session' => false,

    /*
     * Will send the final message as if we are requesting for
     * a response from the user.
     * This will be a workaround for long USSD flow where the session
     * likely to timeout, hence, the user is not be able to see the
     * final response.
     *
     * Setting this to true, the user will always get
     * the final response, but only it will be like we are asking them
     * a response. So it will be always better to add something like
     * "Press Cancel to end."
     */
    'allow_timeout' => true,
    'cancel_msg' => 'Press Cancel to end.',

    'always_send_sms_at_end' => false,
    'sms_sender_name' => '',
    'sms_endpoint' => '',
    'default_error_msg' => 'Invalid input',
];
