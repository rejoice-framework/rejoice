<?php

return [

    'USSD' => [
        'max_page_characters' => 147,
        'max_page_lines'      => 10,
    ],

    'WHATSAPP' => [
        'max_page_characters' => 10000, // Actually no limit.
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
