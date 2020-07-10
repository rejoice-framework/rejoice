<?php
use function Prinx\Dotenv\env;
$ussdCode = env('USSD_CODE');

$menus = [
    'welcome' => [
        'message' => "Welcome to InfoSevo.",
        'actions' => [
            '1' => [
                'display' => 'Delivery market',
                'next_menu' => 'delivery_market_choose_category',
            ],
            '2' => [
                'display' => 'Betting Market',
                'next_menu' => 'betting_market',
            ],
            '3' => [
                'display' => 'Loans Market',
                'next_menu' => 'loans_market',
            ],
            '4' => [
                'display' => 'Insurance Market',
                'next_menu' => 'insurance_market',
            ],
            '5' => [
                'display' => 'Time Killers',
                'next_menu' => 'time_killers',
            ],
            '6' => [
                'display' => 'Business directory',
                'next_menu' => 'business_directory',
            ],
        ],
    ],

    // 'delivery_market_categories' => [
    //     'message' => "Select preferred category",
    //     'actions' => [
    //         '1' => [
    //             'display' => 'Food',
    //             'next_menu' => 'delivery_market_category_chosen',
    //         ],
    //         '2' => [
    //             'display' => 'Groceries',
    //             'next_menu' => 'delivery_market_category_chosen',
    //         ],
    //         '3' => [
    //             'display' => 'Medication',
    //             'next_menu' => 'delivery_market_category_chosen',
    //         ],
    //         '4' => [
    //             'display' => 'Utilities',
    //             'next_menu' => 'delivery_market_category_chosenyyh',
    //         ],
    //         '0' => [
    //             'display' => 'Back',
    //             'next_menu' => '__back',
    //         ],
    //     ],
    // ],

    // 'food' => [
    //     'message' => "Select preferred choice",
    //     'actions' => [
    //         '1' => [
    //             'display' => 'Restaurants',
    //             'next_menu' => 'home_kitchen',
    //         ],
    //         '2' => [
    //             'display' => 'Home Kitchen',
    //             'next_menu' => 'home_kitchen',
    //         ],
    //         '3' => [
    //             'display' => 'Street Food Vendors',
    //             'next_menu' => 'home_kitchen',
    //         ],
    //         '0' => [
    //             'display' => 'Back',
    //             'next_menu' => '__back',
    //         ],
    //     ],
    // ],

    'home_kitchen' => [
        'message' => "Select preferred choice",
        'actions' => [
            '1' => [
                'display' => 'Fiona Foods',
                'save_as' => 'Fiona Foods',
                'next_menu' => 'kitchen_presentation',
            ],
            '2' => [
                'display' => 'Khays Foods',
                'save_as' => 'Khays Foods',
                'next_menu' => 'kitchen_presentation',
            ],
            '3' => [
                'display' => 'Gari Jollof',
                'save_as' => 'Gari Jollof',
                'next_menu' => 'kitchen_presentation',
            ],
            '4' => [
                'display' => 'Beva Foods',
                'save_as' => 'Beva Foods',
                'next_menu' => 'kitchen_presentation',
            ],
            '5' => [
                'display' => 'Talatas Kitchen',
                'save_as' => 'Talatas Kitchen',
                'next_menu' => 'kitchen_presentation',
            ],
            '6' => [
                'display' => 'Soups and Stew Gh',
                'save_as' => 'Soups and Stew Gh',
                'next_menu' => 'kitchen_presentation',
            ],
            '7' => [
                'display' => "Kiki's Pot",
                'save_as' => "Kiki's Pot",
                'next_menu' => 'kitchen_presentation',
            ],
            '8' => [
                'display' => "Sel's Victoria",
                'save_as' => "Sel's Victoria",
                'next_menu' => 'kitchen_presentation',
            ],
            '9' => [
                'display' => 'Ama Fresh Catering',
                'save_as' => 'Ama Fresh Catering',
                'next_menu' => 'kitchen_presentation',
            ],
            '10' => [
                'display' => 'Hajia Apenkwa',
                'save_as' => 'Hajia Apenkwa',
                'next_menu' => 'kitchen_presentation',
            ],
            '11' => [
                'display' => 'Search',
                'next_menu' => 'search_home_kitchen',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'search_home_kitchen' => [
        'message' => "Enter name of Home Kitchen\n(Eg. Fatty Grill)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'kitchen_presentation',
    ],

    // To be managed dynamically at the code's side (in "app/app.php")
    'kitchen_presentation' => [
        'message' => "",
        'actions' => [],
    ],

    'select_meal' => [
        'message' => "Select meal",
        'actions' => [
            '1' => [
                'display' => 'Jollof',
                'save_as' => 'Jollof',
                'next_menu' => 'enter_meal_quantity',
            ],
            '2' => [
                'display' => 'Fried Rice',
                'save_as' => 'Fried Rice',
                'next_menu' => 'enter_meal_quantity',
            ],
            '3' => [
                'display' => 'Waakye',
                'save_as' => 'Waakye',
                'next_menu' => 'enter_meal_quantity',
            ],
            '4' => [
                'display' => 'Banku',
                'save_as' => 'Banku',
                'next_menu' => 'enter_meal_quantity',
            ],
            '5' => [
                'display' => 'Yam and Chips',
                'save_as' => 'Yam and Chips',
                'next_menu' => 'enter_meal_quantity',
            ],
            '6' => [
                'display' => 'Khebab',
                'save_as' => 'Khebab',
                'next_menu' => 'enter_meal_quantity',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'enter_meal_quantity' => [
        'message' => ":selected_meal:\nPlate cost - GHC :plate_cost:\nEnter quantity\n(Eg. 3)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'delivery_market_confirm_order',
    ],

    'delivery_market_confirm_order' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'Confirm',
                'next_menu' => 'order_name',
            ],
            '2' => [
                'display' => 'Order more',
                'next_menu' => 'select_meal',
            ],
            /*             '2'                 => [
            'display'   => 'Make changes',
            'next_menu' => 'delivery_location',
            ], */

            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'order_name' => [
        'message' => "Order name\n(Eg. Kingsley)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],

        ],

        'default_next_menu' => 'delivery_location',
    ],

    'delivery_location' => [
        'message' => "Enter delevery location\n(Eg. Labone)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'payment_option',
    ],

    'payment_option' => [
        'message' => "Payment Option",
        'actions' => [
            '1' => [
                'display' => 'Pay via MoMo',
                'save_as' => 'pay_via_momo',
                'next_menu' => 'end_directory_order',
                // 'next_menu' => 'momo_type',
            ],
            '2' => [
                'display' => 'Pay on delivery',
                'save_as' => 'pay_on_delivery',
                'next_menu' => 'end_directory_order',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    /*
    'momo_type' => [
    'message' => "Select Payment Option",
    'actions' => [
    '1' => [
    'display' => 'MTN MoMo',
    'save_as' => MTN,
    'next_menu' => 'enter_momo_number',
    ],
    '2' => [
    'display' => 'Vodafone Cash',
    'save_as' => VODAFONE,
    'next_menu' => 'enter_momo_number',
    ],
    '3' => [
    'display' => 'AirteltiGo Money',
    'save_as' => AIRTEL,
    'next_menu' => 'enter_momo_number',
    ],
    '0' => [
    'display' => 'Back',
    'next_menu' => '__back',
    ],
    ],
    ],

    'enter_momo_number' => [
    'message' => "Kindly enter the number used for MoMo:",
    'actions' => [
    '0' => [
    'display' => 'Back',
    'next_menu' => '__back',
    ],

    'default_next_menu' => 'end_directory_order',
    ],
    ],
     */

    // Only VODAFONE
    'enter_voucher_code' => [
        'message' => "Enter your voucher code:",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'end_directory_order',
    ],

    'end_directory_order' => [
        'message' => "Thank you for ordering with :kitchen_name:. Your order would be delivered to you within 2 hours.",
    ],

    // Medication Flow
    'medication' => [
        'message' => "Select a preferred choice",
        'actions' => [
            '1' => [
                'display' => 'Pharmacies',
                'next_menu' => 'choose_pharmacy',
            ],
            '2' => [
                'display' => 'Medical services',
                'next_menu' => 'pharmacies',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'choose_pharmacy' => [
        'message' => "Select a preferred pharmacy",
        'actions' => [
            '1' => [
                'display' => 'Add Pharmacy',
                'save_as' => 'Add Pharmacy',
                'next_menu' => 'pharmacy_presentation',
            ],
            '2' => [
                'display' => 'Pill Point Pharmacy',
                'save_as' => 'Pill Point Pharmacy',
                'next_menu' => 'pharmacy_presentation',
            ],
            '3' => [
                'display' => 'Medimart',
                'save_as' => 'Medimart',
                'next_menu' => 'pharmacy_presentation',
            ],
            '4' => [
                'display' => 'Ernest Chemist',
                'save_as' => 'Ernest Chemist',
                'next_menu' => 'pharmacy_presentation',
            ],
            '5' => [
                'display' => 'East Cantonment Pharmacy',
                'save_as' => 'East Cantonment Pharmacy',
                'next_menu' => 'pharmacy_presentation',
            ],
            '6' => [
                'display' => 'Search',
                'next_menu' => 'search_pharmacy',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'search_pharmacy' => [
        'message' => "Enter name of Pharmacy\n(Eg. Dampong Pharmacy)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'pharmacy_presentation',
    ],

    // Dynamically managed in code
    'pharmacy_presentation' => [
        'message' => "",
        'actions' => [],
    ],

    'order_medicine' => [
        'message' => "Enter name of Drug\n(Eg. Paracetamol)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'medicine_quantity',
    ],

    'medicine_quantity' => [
        'message' => "Enter quantity\n(Eg. 3)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'confirm_order_medicine',
    ],

    'confirm_order_medicine' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'Confirm',
                'next_menu' => 'order_name_and_location',
            ],
            '2' => [
                'display' => 'Order more',
                'next_menu' => 'order_medicine',
            ],
            /*             '2' => [
            'display'   => 'Make changes',
            'next_menu' => 'order_medicine',
            ], */
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'order_name_and_location' => [
        'message' => "Order name - Location\n(Eg. James - Ridge)",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'payment_option',
    ],

    /*
    'end_medecine_order_on_delivery' => [
    'message' => "",
    ],
     */

    // Home entertainement flow
    'home_entertainment' => [
        'message' => "Streamming recommandations",
        'actions' => [
            '1' => [
                'display' => 'What to watch',
                'next_menu' => 'videos_entertainment',
            ],
            '2' => [
                'display' => 'Something to listen to',
                'next_menu' => 'audio_entertainment',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'videos_entertainment' => [
        'actions' => [
            '1' => [
                'display' => 'Choices of the week',
                'next_menu' => 'trending_videos',
            ],
            '2' => [
                'display' => "What's trending",
                'next_menu' => 'trending_videos',
            ],
            '3' => [
                'display' => 'Movies',
                'next_menu' => 'movies',
            ],
            '4' => [
                'display' => 'Shows',
                'next_menu' => 'shows',
            ],
            '5' => [
                'display' => 'Documentaries',
                'next_menu' => 'shows',
            ],
            '6' => [
                'display' => 'Others',
                'next_menu' => 'shows',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'trending_videos' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'Trending MOVIES on Netflix',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => "Trending SHOWS on Netflix",
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => 'Youtube Gold',
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => 'Classics',
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '00' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'movies' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'By Genre',
                'next_menu' => 'movies_by_genre',
            ],
            '2' => [
                'display' => "By Mood",
                'next_menu' => 'movies_by_mood',
            ],
            '3' => [
                'display' => 'All the time list',
                'next_menu' => 'movies_by_genre',
            ],
            '4' => [
                'display' => 'Random Jewels',
                'next_menu' => 'movies_by_mood',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '00' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'movies_by_genre' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'Action',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => "Comedy",
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => 'Drama',
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => 'Kids',
                'next_menu' => 'has_not_been_shared',
            ],
            '5' => [
                'display' => 'Thriller',
                'next_menu' => 'has_not_been_shared',
            ],
            '6' => [
                'display' => 'Horror',
                'next_menu' => 'has_not_been_shared',
            ],
            '7' => [
                'display' => 'Documentary',
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '00' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'movies_by_mood' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'Funny',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => "Happy",
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => 'Thought - provoking',
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => 'Sad and emotional',
                'next_menu' => 'has_not_been_shared',
            ],
            '5' => [
                'display' => 'Action - Packed',
                'next_menu' => 'has_not_been_shared',
            ],
            '6' => [
                'display' => 'Mindless fun ',
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '00' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'shows' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'By Genre',
                'next_menu' => 'shows_by_genre',
            ],
            '2' => [
                'display' => "By Mood",
                'next_menu' => 'shows_by_genre',
            ],
            '3' => [
                'display' => 'All the time lists',
                'next_menu' => 'shows_by_genre',
            ],
            '4' => [
                'display' => 'Random jewels',
                'next_menu' => 'shows_by_genre',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'shows_by_genre' => [
        'actions' => [
            '1' => [
                'display' => 'Action',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => "Comedy",
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => 'Drama',
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => 'Kids',
                'next_menu' => 'has_not_been_shared',
            ],
            '5' => [
                'display' => 'Thriller',
                'next_menu' => 'has_not_been_shared',
            ],
            '6' => [
                'display' => 'Sci-Fi',
                'next_menu' => 'has_not_been_shared',
            ],
            '7' => [
                'display' => 'Fantasy',
                'next_menu' => 'has_not_been_shared',
            ],
            '8' => [
                'display' => 'Historic/Epic',
                'next_menu' => 'has_not_been_shared',
            ],
            '9' => [
                'display' => 'Horror',
                'next_menu' => 'has_not_been_shared',
            ],
            '10' => [
                'display' => 'Discoveries',
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'audio_entertainment' => [
        'actions' => [
            '1' => [
                'display' => 'Music playlist',
                'next_menu' => 'music_playlist',
            ],
            '2' => [
                'display' => "Podcasts",
                'next_menu' => 'music_playlist',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'music_playlist' => [
        'actions' => [
            '1' => [
                'display' => 'By Genre',
                'next_menu' => 'music_playlist_by_genre',
            ],
            '2' => [
                'display' => "By Mood",
                'next_menu' => 'music_playlist_by_mood',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'music_playlist_by_genre' => [
        'actions' => [
            '1' => [
                'display' => 'RnB',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => "Country",
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => "Rock",
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => "Afro pop",
                'next_menu' => 'has_not_been_shared',
            ],
            '5' => [
                'display' => "Raggae",
                'next_menu' => 'has_not_been_shared',
            ],
            '6' => [
                'display' => "Gospel",
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'music_playlist_by_genre' => [
        'actions' => [
            '1' => [
                'display' => 'Calm',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => "Throwback",
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => "Party",
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => "Study",
                'next_menu' => 'has_not_been_shared',
            ],
            '5' => [
                'display' => "Feel happy",
                'next_menu' => 'has_not_been_shared',
            ],
            '6' => [
                'display' => "Fitness and exercise",
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'loans_market' => [
        'message' => "Welcome to InfoSevo Loans portal",
        'actions' => [
            '1' => [
                'display' => 'Find a loan',
                'next_menu' => 'request_loan',
            ],
            '2' => [
                'display' => 'Request a loan',
                'next_menu' => 'request_loan',
            ],
            '3' => [
                'display' => 'Find a financial institution',
                'next_menu' => 'find_financial_institution',
            ],
            '4' => [
                'display' => 'My credit score',
                'next_menu' => 'my_credit_score',
            ],
            '5' => [
                'display' => 'My loans ',
                'next_menu' => 'my_loans',
            ],
            '6' => [
                'display' => 'Fund a loan',
                'next_menu' => 'request_loan',
            ],
            '7' => [
                'display' => 'Utilities',
                'next_menu' => 'buy_and_pay_your_utilities',
            ],
            '8' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'request_loan' => [
        'message' => "Your loan request will be shared with prospective individual lenders",
        'actions' => [
            '1' => [
                'display' => 'Post a loan request',
                'next_menu' => 'post_loan_request',
            ],
            '2' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'post_loan_request' => [
        'message' => "Please specify the type of loan required",
        'actions' => [
            '1' => [
                'display' => 'Personal loan',
                'next_menu' => 'find_loan',
            ],
            '2' => [
                'display' => 'Business loan',
                'next_menu' => 'find_loan',
            ],
        ],
    ],

    'find_loan' => [
        'message' => "Let's find a lender for you.\nHow much do you wish to borrow?",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'find_loan_tenor',
    ],

    'find_loan_business' => [
        'message' => "Let's find the best loan for you.\nHow much do you wish to borrow?",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'find_loan_tenor',
    ],

    'find_loan_tenor' => [
        'message' => "How long do you wish to borrow this amount for?",
        'actions' => [
            '1' => [
                'display' => '3 months',
                'next_menu' => 'find_loan_employment_details',
            ],
            '2' => [
                'display' => '6 months',
                'next_menu' => 'find_loan_employment_details',
            ],
            '3' => [
                'display' => '1 year',
                'next_menu' => 'find_loan_employment_details',
            ],
            '4' => [
                'display' => '2 years',
                'next_menu' => 'find_loan_employment_details',
            ],
            '5' => [
                'display' => 'Other',
                'next_menu' => 'find_loan_employment_details',
            ],
        ],
    ],

    'find_loan_employment_details' => [
        'message' => "Are you employed?",
        'actions' => [
            '1' => [
                'display' => 'Yes',
                'save_as' => 1,
                'next_menu' => 'find_loan_monthly_income',
            ],
            '2' => [
                'display' => 'No',
                'save_as' => 0,
                'next_menu' => 'find_loan_monthly_income',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'find_loan_monthly_income' => [
        'message' => "What's your monthly income in GHS?",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'find_loan_options',
    ],

    'find_loan_monthly_income_business' => [
        'message' => "What's your average monthly revenue in GHS?",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'find_loan_options',
    ],

    'find_loan_options' => [
        'message' => "These are the available options for you\nPlease select your preferred plan",
        'actions' => [
            '1' => [
                'display' => 'MTN Quikloans: 4.5%pm',
                'next_menu' => 'find_loan_apply',
            ],
            '2' => [
                'display' => 'Vodafone loans: 5%pm',
                'next_menu' => 'find_loan_apply',
            ],
            '9' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'find_loan_apply' => [
        'message' => "CBG personal loan provides loans up to 100,000 at an interest rate of 4.5%",
        'actions' => [
            '1' => [
                'display' => 'Apply Now',
                'next_menu' => 'end_find_loan_apply',
            ],
            '2' => [
                'display' => 'Back to loan options',
                'next_menu' => '__back',
            ],
            '3' => [
                'display' => 'Back to loans main page',
                'next_menu' => 'loans_market',
            ],
        ],
    ],

    'end_find_loan_apply' => [
        'message' => "You will receive the link to the CBG personal loan application form via SMS. Thank you.",
        'actions' => [
            '1' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '2' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    // Financial institution
    'find_financial_institution' => [
        'message' => "Please select an institution type below",
        'actions' => [
            '1' => [
                'display' => 'Banks',
                'next_menu' => 'find_financial_institution_list',
            ],
            '2' => [
                'display' => 'Micro-finance',
                'next_menu' => 'find_financial_institution_list',
            ],
            '3' => [
                'display' => 'Savings and Loans',
                'next_menu' => 'find_financial_institution_list',
            ],
        ],
    ],

    'find_financial_institution_list' => [
        'message' => "Please select a bank from the list below to see their loan products",
        'actions' => [
            '1' => [
                'display' => 'CBG',
                'next_menu' => 'find_financial_institution_company_page',
            ],
            '2' => [
                'display' => 'ADB',
                'next_menu' => 'find_financial_institution_company_page',
            ],
            '3' => [
                'display' => 'Ecobank',
                'next_menu' => 'find_financial_institution_company_page',
            ],
            '4' => [
                'display' => 'Absa',
                'next_menu' => 'find_financial_institution_company_page',
            ],
            '5' => [
                'display' => 'More',
                'next_menu' => 'find_financial_institution_company_page',
            ],
        ],
    ],

    'find_financial_institution_company_page' => [
        'message' => "Consolidated Bank Ghana",
        'actions' => [
            '1' => [
                'display' => 'Products/Plans',
                'next_menu' => 'find_financial_institution_company_page',
            ],
            '2' => [
                'display' => 'Contacts',
                'next_menu' => 'find_financial_institution_company_page',
            ],
        ],
    ],

    'find_financial_institution_company_products' => [
        'message' => "CGB Loan products",
        'actions' => [
            '1' => [
                'display' => 'Salary advance',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => 'Asset financing',
                'next_menu' => 'has_not_been_shared',
            ],
        ],
    ],

    'find_financial_institution_company_loan_details' => [
        'message' => "CGB Salary advance loan provides loans up to 100,000 at an interest rate of 4.5%.",
        'actions' => [
            '1' => [
                'display' => 'Apply Now',
                'next_menu' => 'find_financial_institution_company_contacts',
            ],
            '2' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '3' => [
                'display' => 'Back to loans main page',
                'next_menu' => 'loans_market',
            ],
        ],
    ],

    'find_financial_institution_company_contacts' => [
        'message' => "CGB\nAddress:\nTelephone:\nEmail:",
        'actions' => [
            '1' => [
                'display' => 'Receive contacts via text',
                'next_menu' => 'find_financial_institution_company_contacts_sent',
            ],
            '2' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'find_financial_institution_company_contacts_sent' => [
        'message' => "CBG Contacts details sent!",
    ],

    'outstanding_loan_applications' => [
        'message' => "List of active loan requests",
        'actions' => [
            '1' => [
                'display' => 'Loan 232: GHS300',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => 'Loan 545: GHS600',
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => 'Loan 673: GHS200',
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => 'Loan 098: GHS350',
                'next_menu' => 'has_not_been_shared',
            ],
            '5' => [
                'display' => 'Loan 435: GHS900',
                'next_menu' => 'has_not_been_shared',
            ],
            '6' => [
                'display' => 'Loan 356: GHS100',
                'next_menu' => 'has_not_been_shared',
            ],
            '7' => [
                'display' => 'More',
                'next_menu' => '__end',
            ],
        ],
    ],

    'find_financial_institution_loan_application_details' => [
        'message' => "Loan application 232: Amount required: GHS300\nPayment Tenor: 3 months\nBorrower employed?: Yes\nSalary: GH 300",
        'actions' => [
            '1' => [
                'display' => 'Get borrower\'s credit score',
                'next_menu' => 'find_financial_institution_credit_score',
            ],
            '2' => [
                'display' => 'Contact borrower',
                'next_menu' => 'find_financial_institution_borrower_contact',
            ],
            '3' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'find_financial_institution_credit_score' => [
        'message' => "Credit score of profile ID 0544******32 will cost 10GHS",
        'actions' => [
            '1' => [
                'display' => 'Get borrower\'s credit score',
                'next_menu' => 'find_financial_institution_profile',
            ],
            '2' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'find_financial_institution_profile' => [
        'message' => "Profile ID:0544******32\nCredit rating",
        'actions' => [
            '1' => [
                'display' => 'Back to loan applications',
                'next_menu' => 'loans_market',
            ],
            '2' => [
                'display' => 'Contact borrower',
                'next_menu' => 'find_financial_institution_borrower_contact',
            ],
            '3' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'find_financial_institution_borrower_contact' => [
        'message' => "Borrower contacts 054474873632\nLoan required: GHS 300",
        'actions' => [
            '1' => [
                'display' => 'Back to loan applications',
                'next_menu' => 'loans_market',
            ],
            '2' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'my_loans' => [
        'message' => "Your loan requests",
        'actions' => [
            '1' => [
                'display' => 'Loan 345: GHS 300',
                'next_menu' => 'show_loan',
            ],
            '2' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'show_loan' => [
        'message' => "Loan 345: GHS 300n\nDate posted: 09/04/2020\nViews: 3",
        'actions' => [
            '1' => [
                'display' => 'Edit request',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => 'Delete request',
                'next_menu' => 'delete_loan',
            ],
        ],
    ],

    'delete_loan' => [
        'message' => "Your Loan 345 has been deleted",
        'actions' => [
            '1' => [
                'display' => 'Back to main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'buy_and_pay_your_utilities' => [
        'message' => "Your Loan 345 has been deleted",
        'actions' => [
            '1' => [
                'display' => 'Airtime',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => 'ECG',
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => 'GWCL',
                'next_menu' => 'has_not_been_shared',
            ],
            '4' => [
                'display' => 'TV',
                'next_menu' => 'has_not_been_shared',
            ],
        ],
    ],

    'betting_market' => [
        'message' => 'Welcome to the InfoSevo Betting Portal.',
        'actions' => [
            '1' => [
                'display' => 'Bet against friend',
                'next_menu' => 'bet_initiator_name',
            ],
            '2' => [
                'display' => "Betting Houses",
                'next_menu' => 'betting_houses',
            ],
            '3' => [
                'display' => "My bets",
                'next_menu' => 'my_bets',
            ],
            '4' => [
                'display' => "Betting tips",
                'next_menu' => 'betting_tips_subscription',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'bet_initiator_name' => [
        'message' => "Good luck with your bet!\nPlease enter your name below.:option:",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'validate' => 'alpha|min_len:2|max_len:50',
        'default_next_menu' => 'bet_opponent_name',
    ],

    'bet_opponent_name' => [
        'message' => 'Please enter the name of your opponent:',
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        // 'validate' => 'alpha|min_len:3|max_len:50',
        'validate' => 'name',
        'default_next_menu' => 'bet_opponent_number',
    ],

    'bet_opponent_number' => [
        'message' => "Please enter :opponent_name:'s phone number below:",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'bet_statement',
        'validate' => 'tel',
    ],

    'bet_statement' => [
        'message' => "Please state the bet",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'validate' => 'alpha|min_len:3|max_len:150',
        'default_next_menu' => 'bet_settlement_date',
    ],

    'bet_settlement_date' => [
        'message' => "By what date will the outcome be decided? (dd/mm/yyyy)\nEg: :example_date:",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'validate' => 'date:j/n/Y',
        'default_next_menu' => 'bet_referee_number',
    ],

    'bet_referee_number' => [
        'message' => "A referee has the final decision on who wins the bet.\nPlease provide a referee's number:",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'validate' => 'tel',
        'default_next_menu' => 'bet_wagering',
    ],

    'bet_wagering' => [
        'message' => "How much are you wagering?",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'validate' => 'amount|min:0.1',
        'default_next_menu' => 'bet_with_friend_last_feedback',
    ],

    'bet_with_friend_last_feedback' => [
        'message' => 'Thank you! Kindly select option 1 to deposit your wager. You will receive a mobile money prompt.',
        'actions' => [
            '1' => [
                'display' => 'Confirm',
                'next_menu' => 'bet_with_friend_ends',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => '__end',
    ],

    'bet_with_friend_ends' => [
        'actions' => [
            '0' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],

        'default_next_menu' => '__end',
    ],

    'my_bets' => [
        'message' => "Hi, you have :bets: outstanding.",
    ],

    'select_active_bet' => [],

    'betting_details' => [],

    'bet_winner' => [
        'message' => "Who won this bet?\n",
        'actions' => [],
    ],

    'bet_winner_confirmation' => [
        'message' => "Please confirm that Player :player_id: (:player_name:) won this bet.\n:player_name: will receive :winner_ammount: as winnings.",
        'actions' => [
            '1' => [
                'display' => 'Yes',
                'next_menu' => 'reward_bet_winner',
            ],
            '2' => [
                'display' => 'No',
                'next_menu' => '__back',
            ],
        ],
    ],

    'reward_bet_winner' => [
        'message' => "Great!:winner_name: shall receive winnings by mobile money!\nThank you.",
        'actions' => [
            '1' => [
                'display' => 'Back to betting menu',
                'next_menu' => 'betting_market',
            ],
        ],
    ],

    'cancel_bet' => [
        'message' => "Are you sure you wish to cancel this bet?\nYour wager shall be forfeited.",
        'actions' => [
            '1' => [
                'display' => 'Yes',
                'save_as' => 1,
                'next_menu' => 'betting_market',
            ],
            '2' => [
                'display' => 'No',
                'save_as' => 0,
                'next_menu' => 'betting_market',
            ],
        ],
    ],

    'cancel_bet_message' => [
        'message' => "Bet :bet_id: has been cancelled. Wagers have been returned.\nThank you",
        'actions' => [
            '1' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    // Betting houses
    'betting_houses' => [
        'message' => "Select an option",
        'actions' => [
            '1' => [
                'display' => 'Betway',
                'save_as' => 'Betway',
                'next_menu' => 'available_bets',
            ],
            '2' => [
                'display' => 'Soccerbet',
                'save_as' => 'Soccerbet',
                'next_menu' => 'available_bets',
            ],
            '3' => [
                'display' => '1xbet',
                'save_as' => '1xbet',
                'next_menu' => 'available_bets',
            ],
        ],
    ],

    'available_bets' => [
        'message' => ":bet_house: bets available",
        'actions' => [
            '1' => [
                'display' => 'Match of the day',
                'next_menu' => 'match_of_the_day',
            ],
            '2' => [
                'display' => 'Popular events',
                'next_menu' => 'popular_events',
            ],
        ],
    ],

    'popular_events' => [
        'message' => ":bet_house: bets available",
        'actions' => [
            '1' => [
                'display' => 'Man U vs Chelsea',
                'next_menu' => 'match_of_the_day',
            ],
            '2' => [
                'display' => 'Arsenal vs Newcastle',
                'next_menu' => 'match_of_the_day',
            ],
            '3' => [
                'display' => 'Real Madrid  vs Getafe',
                'next_menu' => 'match_of_the_day',
            ],
            '4' => [
                'display' => 'Liverpool vs Burnley',
                'next_menu' => 'match_of_the_day',
            ],
        ],
    ],

    'match_of_the_day' => [
        'message' => "EPL: Man U vs Chelsea\nOffered bets",
        'actions' => [
            '1' => [
                'display' => 'Home win',
                'save_as' => 'Home win',
                'next_menu' => 'match_of_the_day_bet_confirm',
            ],
            '2' => [
                'display' => 'Away win',
                'save_as' => 'Away win',
                'next_menu' => 'match_of_the_day_bet_confirm',
            ],
            '3' => [
                'display' => 'Draw',
                'save_as' => 'Draw',
                'next_menu' => 'match_of_the_day_bet_confirm',
            ],
            '4' => [
                'display' => 'Over 2.5 goals',
                'save_as' => 'Over 2.5 goals',
                'next_menu' => 'match_of_the_day_bet_confirm',
            ],
            '5' => [
                'display' => 'Under 2.5 goals',
                'save_as' => 'Under 2.5 goals',
                'next_menu' => 'match_of_the_day_bet_confirm',
            ],
        ],
    ],

    'match_of_the_day_bet_confirm' => [
        'message' => "Man U vs Chelsea\nSelected bet: Away win\nOdds: 1.9",
        'actions' => [
            '1' => [
                'display' => 'Bet now',
                'next_menu' => 'match_of_the_day_wager',
            ],
            '2' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'match_of_the_day_wager' => [
        'message' => "How much do you wish to bet?",
        'actions' => [
            '1' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'end_match_of_the_day_bet',
    ],

    'end_match_of_the_day_bet' => [
        'message' => "Please follow the mobile money prompt to fund your bet. Thank you!",
    ],

    // Betting tips subscription
    'betting_tips_subscription' => [
        'message' => "Receive premium well researched betting tips from our professional pundits at GHC0.50 daily!",
        'actions' => [
            '1' => [
                'display' => 'Subscribe',
                'next_menu' => 'end_betting_tips_subscription',
            ],
            '2' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'end_betting_tips_subscription' => [
        'message' => "You are now subscibed!",
        'actions' => [
            '1' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    // Completely managed by the menu entity
    'bets_history' => [],

    // Bet presentation
    'bet_history_details' => [
        'message' => "Bet 253: between Eric and Korle.\nStatus: completed\nWager: GHS 50\nDesc: Ronaldo to win golden boot",
        'actions' => [
            '9' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'insurance_market' => [
        'message' => "Welcome to InfoSevo insurance portal.\nPlease select insurance type of interest.",
        'actions' => [
            '1' => [
                'display' => 'Health',
                'next_menu' => 'health_insurance',
            ],
            '2' => [
                'display' => 'General',
                'next_menu' => 'general_insurance',
            ],
            '3' => [
                'display' => 'Life',
                'next_menu' => 'life_insurance',
            ],
        ],
    ],

    'health_insurance' => [
        'message' => "Health insurance - How can we assist today?",
        'actions' => [
            '1' => [
                'display' => 'Find me a plan',
                'next_menu' => 'health_insurance_plan',
            ],
            '2' => [
                'display' => 'Find health insurance company',
                'next_menu' => 'health_insurance_company',
            ],
        ],
    ],

    'health_insurance_seeker_name' => [
        'message' => "What is your name?",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'health_insurance_seeker_dob',
    ],

    'health_insurance_seeker_dob' => [
        'message' => "What is your date of birth?",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'health_insurance_seeker_gender',
    ],

    'health_insurance_seeker_gender' => [
        'message' => "What is your gender?",
        'actions' => [
            '1' => [
                'display' => 'Female',
                'save_as' => 'F',
                'next_menu' => 'health_insurance_cover_family',
            ],
            '2' => [
                'display' => 'Male',
                'save_as' => 'M',
                'next_menu' => 'health_insurance_cover_family',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'health_insurance_cover_family' => [
        'message' => "Do you wish to include your family in this cover?",
        'actions' => [
            '1' => [
                'display' => 'Yes',
                'save_as' => 1,
                'next_menu' => 'health_insurance_products',
            ],
            '2' => [
                'display' => 'No',
                'save_as' => 0,
                'next_menu' => 'health_insurance_products',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'health_insurance_products' => [
        'message' => "Here are some suitable products for you",
        'actions' => [
            '1' => [
                'display' => 'Kaiser Health plan',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => 'Acacia Healthcare',
                'next_menu' => 'has_not_been_shared',
            ],
            '3' => [
                'display' => 'BIMA health',
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'health_insurance_products_presentation' => [
        'message' => "Kaiser Ayarisa offers the best care for you",
        'actions' => [
            '1' => [
                'display' => 'Get more details',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => 'Apply now',
                'next_menu' => 'has_not_been_shared',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'health_insurance_products_link_shared' => [
        'message' => "You will now be redirected to the service menu",
        'actions' => [
            '1' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '2' => [
                'display' => 'Back to insurance main page',
                'next_menu' => 'insurance_market',
            ],
            '0' => [
                'display' => 'Back to main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'health_insurance_products_app_link_shared' => [
        'message' => "You will now be redirected to the partner service menu",
        'actions' => [
            '1' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '2' => [
                'display' => 'Back to insurance main page',
                'next_menu' => 'insurance_market',
            ],
            '0' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'health_insurance_companies' => [
        'message' => "General/Life/Health Insurance companies",
        'actions' => [
            '1' => [
                'display' => 'Kaiser',
                'next_menu' => 'health_insurance_company_page',
            ],
            '2' => [
                'display' => 'SIC',
                'next_menu' => 'health_insurance_company_page',
            ],
            '3' => [
                'display' => 'GLife',
                'next_menu' => 'health_insurance_company_page',
            ],
            '4' => [
                'display' => 'etc.',
                'next_menu' => 'health_insurance_company_page',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'health_insurance_company_page' => [
            'message' => ":company_name: Insurance",
            'actions' => [
                '1' => [
                    'display' => 'Products/Plans',
                    'next_menu' => 'health_insurance_company_plans',
                ],
                '2' => [
                    'display' => 'Contacts',
                    'next_menu' => 'health_insurance_company_plans',
                ],
                '0' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],
            ],
        ],

        'health_insurance_company_plans' => [
            'message' => ":company_name: Insurance\nAddress: 254, Swaniker Link\nTel: 0272501017\nEmail:",
            'actions' => [
                '1' => [
                    'display' => 'Receive contacts via text',
                    'next_menu' => 'send_health_insurance_via_text',
                ],
                '2' => [
                    'display' => 'Main menu',
                    'next_menu' => '__welcome',
                ],
            ],
        ],

        'send_health_insurance_via_text' => [
            'message' => ":company_name: contacts details sent!",
            'actions' => [
                '1' => [
                    'display' => 'Main menu',
                    'next_menu' => '__welcome',
                ],
            ],
        ],

        'general_insurance' => [
            'message' => "General Insurance - How can we assist today?",
            'actions' => [
                '1' => [
                    'display' => 'Find me a plan',
                    'next_menu' => 'find_general_insurance_plan',
                ],
                '2' => [
                    'display' => 'Find a general insurance company',
                    'next_menu' => 'find_general_insurance_company',
                ],
            ],
        ],

        'find_general_insurance_plan' => [
            'message' => "What type of General insurance are you looking for?",
            'actions' => [
                '1' => [
                    'display' => 'Auto',
                    'next_menu' => 'find_general_insurance_plan_property_value',
                ],
                '2' => [
                    'display' => 'House',
                    'next_menu' => 'find_general_insurance_plan_property_value',
                ],
            ],
        ],

        'find_general_insurance_plan_property_value' => [
            'message' => "What's the value of your property/car?",
            'actions' => [
                '0' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],
            ],

            'default_next_menu' => 'find_general_insurance_plan_property_registered',
        ],

        'find_general_insurance_plan_property_value' => [
            'message' => "Is your car/property:",
            'actions' => [
                '1' => [
                    'display' => 'Registered',
                    'save_as' => 1,
                    'next_menu' => 'find_general_insurance_plan_list',
                ],
                '2' => [
                    'display' => 'Registered',
                    'save_as' => 0,
                    'next_menu' => 'find_general_insurance_plan_list',
                ],
                '0' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],
            ],
        ],

        'find_general_insurance_plan_list' => [
            'message' => "Here are some suitable Auto plans for you",
            'actions' => [
                '1' => [
                    'display' => 'SIC',
                    'save_as' => 'SIC',
                    'next_menu' => 'find_general_insurance_plan_list_ins',
                ],
                '2' => [
                    'display' => 'Enterprise',
                    'save_as' => 'Enterprise',
                    'next_menu' => 'find_general_insurance_plan_list_ins',
                ],
                '0' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],
            ],
        ],

        'find_general_insurance_plan_list_ins' => [
            'message' => "You will now be redirecxted to the service menu",
            'actions' => [
                '1' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],
                '2' => [
                    'display' => 'Back to insurance main page',
                    'next_menu' => '__back',
                ],
            ],
        ],

    ],

    // Time Killers

    'time_killers' => [
        'message' => "Welcome to time Killers",
        'actions' => [
            '1' => [
                'display' => 'Streaming recommendations',
                'next_menu' => 'stream_rec',
            ],
            '2' => [
                'display' => 'Social groups',
                'next_menu' => 'social_clubs',
            ],
            '3' => [
                'display' => 'Games',
                'next_menu' => 'games',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'stream_rec' => [
        'message' => "Enjoy the best movies shows and music out there",
        'actions' => [
            '1' => [
                'display' => 'Something to watch',
                'next_menu' => 'videos_entertainment',
            ],
            '2' => [
                'display' => 'Something to listen to',
                'next_menu' => 'audio_entertainment',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'social_clubs' => [
        'message' => "The best pages and groups to join",
        'actions' => [
            '1' => [
                'display' => 'Club of the week',
                'next_menu' => 'social_clubs_category',
            ],
            '2' => [
                'display' => 'Search by category',
                'next_menu' => 'social_clubs_category',
            ],
            '3' => [
                'display' => 'Search by Platform',
                'next_menu' => 'social_club_platforms',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'social_clubs_category' => [
        'message' => "The best pages and groups to join",
        'actions' => [
            '1' => [
                'display' => 'Business',
                'save_as' => 'Business',
                'next_menu' => 'social_clubs_list',
            ],
            '2' => [
                'display' => 'Business',
                'save_as' => 'Business',
                'next_menu' => 'social_clubs_list',
            ],
            '3' => [
                'display' => 'Football',
                'save_as' => 'Football',
                'next_menu' => 'social_clubs_list',
            ],
            '4' => [
                'display' => 'General',
                'save_as' => 'General',
                'next_menu' => 'social_clubs_list',
            ],
            '5' => [
                'display' => 'Adult',
                'save_as' => 'Adult',
                'next_menu' => 'social_clubs_list',
            ],
            '6' => [
                'display' => 'Betting',
                'save_as' => 'Betting',
                'next_menu' => 'social_clubs_list',
            ],
            '7' => [
                'display' => 'Entertainment',
                'save_as' => 'Entertainment',
                'next_menu' => 'social_clubs_list',
            ],
        ],
    ],

    'social_clubs_list' => [
        'message' => ":club_name: groups and pages",
        'actions' => [
            '1' => [
                'display' => 'Bitcoin group',
                'save_as' => 'Bitcoin group',
                'next_menu' => 'social_club_presentation',
            ],
            '2' => [
                'display' => 'China business group',
                'save_as' => 'China business group',
                'next_menu' => 'social_club_presentation',
            ],
            '3' => [
                'display' => 'Herbalife group',
                'save_as' => 'Herbalife group',
                'next_menu' => 'social_club_presentation',
            ],
        ],
    ],

    'social_clubs_list' => [
        'message' => "Group: :group_name:\nLearn and trade bitcoin\nPlatform: Whatsapp",
        'actions' => [
            '1' => [
                'display' => 'Join group',
                'next_menu' => 'send_social_club_link',
            ],
            '2' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'send_social_club_link' => [
        'message' => "A link to the group: Bitcoin Traders has been shared by SMS.",
        'actions' => [
            '1' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'social_club_platforms' => [
        'message' => "Find the best clubs on:",
        'actions' => [
            '1' => [
                'display' => 'Whatsapp',
                'next_menu' => 'social_clubs_category',
            ],
            '2' => [
                'display' => 'Facebook',
                'next_menu' => 'social_clubs_category',
            ],
            '3' => [
                'display' => 'Linkedin',
                'next_menu' => 'social_clubs_category',
            ],
            '0' => [
                'display' => 'Back to main menu',
                'next_menu' => 'social_clubs_category',
            ],
        ],
    ],

    /*
    'social_club_list_on_platform' => [
    'message' => "The best groups on Whatsapp:",
    'actions' => [
    '1' => [
    'display' => 'Business',
    'next_menu' => '__welcome',
    ],
    '2' => [
    'display' => 'Religion',
    'next_menu' => '__welcome',
    ],
    '3' => [
    'display' => 'Football',
    'next_menu' => '__welcome',
    ],
    '4' => [
    'display' => 'General',
    'next_menu' => '__welcome',
    ],
    '5' => [
    'display' => 'Adult',
    'next_menu' => '__welcome',
    ],
    '6' => [
    'display' => 'Betting',
    'next_menu' => '__welcome',
    ],
    '7' => [
    'display' => 'Entertainment',
    'next_menu' => '__welcome',
    ],
    '0' => [
    'display' => 'Back to main menu',
    'next_menu' => '__welcome',
    ],
    ],
    ],

     */

    'games' => [
        'message' => "Exciting games at your fingertips:",
        'actions' => [
            '1' => [
                'display' => 'Gameboxx Trivia',
                'next_menu' => 'game_presentation',
            ],
            '2' => [
                'display' => 'Grab it',
                'next_menu' => 'game_presentation',
            ],
            '3' => [
                'display' => 'PTB',
                'next_menu' => 'game_presentation',
            ],
            '0' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'game_presentation' => [
        'message' => "Gameboxx, the game for genuises",
        'actions' => [
            '1' => [
                'display' => 'Play Now',
                'next_menu' => 'has_not_been_shared',
            ],
            '2' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
        ],
    ],

    'business_directory' => [
        'message' => "Select an option",
        'actions' => [
            '1' => [
                'display' => 'Search by company name',
                'next_menu' => 'business_search_input',
                'save_as' => 'search_by_name',
            ],
            '2' => [
                'display' => 'Search by company USSD',
                'next_menu' => 'business_search_input',
                'save_as' => 'search_by_ussd',
            ],
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],
    ],

    'business_search_input' => [
        'message' => "Enter company's :type_search:",
        'actions' => [
            '0' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
        ],

        'default_next_menu' => 'business_search_result',
    ],

    'business_search_result' => [
        'message' => "",
        'actions' => [
            '1' => [
                'display' => 'Search another company',
                'next_menu' => 'business_directory',
            ],
            '2' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
            '0' => [
                'display' => 'End',
                'next_menu' => '__end',
            ],
        ],
    ],

    'has_not_been_shared' => [
        'message' => "Coming soon.\n\nKindly select an option",
        'actions' => [
            '1' => [
                'display' => 'Back',
                'next_menu' => '__back',
            ],
            '2' => [
                'display' => 'Main menu',
                'next_menu' => '__welcome',
            ],
            '3' => [
                'display' => 'End',
                'next_menu' => '__end',
            ],
        ],
    ],
];

return $menus;
