<?php

return [

    'USSD' => [
        'max_page_characters' => 147,
        'max_page_lines'      => 10,
    ],

    /*
     * There is no actual message character or line limit for the WhatsApp channel
     */
    'WHATSAPP' => [
        'max_page_characters' => 10000,
        'max_page_lines'      => 10000,
    ],

    'CONSOLE' => [
        'max_page_characters' => 147,
        'max_page_lines'      => 10,
    ],

    'DEFAULT' => [
        'max_page_characters' => 147,
        'max_page_lines'      => 10,
    ],

];
