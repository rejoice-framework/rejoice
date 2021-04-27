<?php

return [
    'message_ask_user_before_reload_previous_session' => 'Do you want to continue from where you left?',

    'previous_session_trigger' => '1',

    'previous_session_display' => 'Continue',

    'restart_session_trigger' => '2',

    'restart_session_display' => 'Restart',

    /*
     * Default character that will trigger a go back, if the user can go back
     * on the current menu
     */
    'back_action_trigger' => '0',

    /*
     * Default 'go back' expression to display to the user, if the user can go
     * back on the current menu
     */
    'back_action_display' => 'Back',

    /*
     * Default character that will trigger a go forward, if the user can go
     * forward on the current menu
     */
    'splitted_menu_next_trigger' => '00',

    /*
     * Default 'go forward' expression to display to the user, if they can go
     * forward on the current menu
     */
    'splitted_menu_display' => 'Next page',

    /*
     * Default character that will trigger a go forward, if the user can go
     * forward on the current menu
     */
    'paginate_forward_trigger' => '00',

    /*
     * Default 'paginate forward' expression
     */
    'paginate_forward_display' => 'More',

    /*
     * Default character that will trigger a go forward, if the user can go
     * forward on the current menu
     */
    'welcome_action_trigger' => '01',

    /*
     * Default 'paginate forward' expression
     */
    'welcome_action_display' => 'Main menu',

    'end_trigger' => '02',

    'end_display' => 'End',

    /*
     * Default message that will be displayed to the user if an `end`
     * method has been called without passing any message to display.
     */
    'default_end_message' => 'Thank you.',

    'default_error_message' => 'Invalid Input.',

    'unhandled_action_message' => 'Action not handled.',

    'empty_response_error' => 'Empty response not allowed.',

    'application_failed_message' => 'Sorry, an error happened.',

    /*
     * Default character that will trigger a paginate back, if the user can
     * paginate back on the current menu
     */
    'paginate_back_trigger' => '0',

    /*
     * Default 'paginate back' expression
     */
    'paginate_back_display' => 'Back',

    /*
     * Number of pagination item to show per page when paginating
     */
    'pagination_default_to_show_per_page' => 5,

    /*
     * By default actions will be two new lines under the message
     */
    'seperator_message_and_actions' => "\n\n",

    'seperator_trigger_and_display' => ' ',

    /*
     * Let's say you have an option:
     *  1. Register
     *
     * Modify this to ') ' to produce:
     * 1) Register
     */
    'trigger_decorator' => '. ',

    'namespace_delimiter' => '::',

    /*
     * If allow_timeout is false, this message will be appended to the last
     * message to let the user press the cancel button to terminate the request.
     * This will be displayed only if the user is assessing the application via USSD.
     * If the user does not press cancel, and rather send a response, the
     * application itself automatically destroyed the session
     */
    'cancel_message' => 'Press Cancel to end.',

    /*
     * Space between the message and a cancel message
     */
    'seperator_menu_string_and_cancel_message' => "\n\n",
];
