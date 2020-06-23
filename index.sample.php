<?php
/**
 * (c) Nuna Akpaglo <princedorcis@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__ . '/vendor/prinx/rejoice/src/USSD.php';

use function Prinx\Dotenv\env;
use Prinx\Rejoice\Kernel;

define('ENV', 'development');

class USSDApp
{
    protected $default_date_format = 'd/m/Y';

    protected $appParams = [
        'id' => 'first_ussd_app',
        'environment' => 'dev',
        'end_on_user_error' => false,

        'always_start_new_session' => false,
        'ask_user_before_reload_last_session' => true,

        'always_send_sms' => true,
        'sms_sender_name' => 'BASICAPP',
        // 'sms_endpoint' => '',

        'back_action_thrower' => '0',
        'back_action_display' => 'Back',
        'splitted_menu_next_thrower' => '99',
        'splitted_menu_display' => 'More',

        'default_end_msg' => 'Thank you.',
        'default_error_msg' => 'Invalid Input.',
    ];

    protected $ussd;

    protected $menus = [
        'welcome' => [
            'message' => "Welcome.\nSelect an option",
            'actions' => [
                '1' => [
                    'display' => 'Am I working ?',
                    'next_menu' => 'verify_working',
                ],
                '2' => [
                    'display' => 'What is the date?',
                    'next_menu' => 'show_date',
                ],
                '3' => [
                    'display' => 'Caluculate age',
                    'next_menu' => 'get_birthdate',
                ],
                '4' => [
                    'display' => 'Say Goodbye',
                    'next_menu' => 'say_goodbye',
                ],
            ],
        ],

        'verify_working' => [
            'message' => "Of course, I'm working!",
        ],

        'show_date' => [
            'message' => "Today is :date:!",
            'actions' => [
                '1' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],
                '0' => [
                    'display' => 'End',
                    'next_menu' => '__end',
                ],
            ],
        ],

        'get_birthdate' => [
            'message' => "Enter your birthdate (dd/mm/yyyy)\nOr enter 0 to go back:",
            'actions' => [
                '0' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],

                'default_next_menu' => 'show_age',
            ],
        ],

        'show_age' => [
            'message' => "You are :age: years old!",
            'actions' => [
                '0' => [
                    'display' => 'Back',
                    'next_menu' => '__back',
                ],
                '1' => [
                    'display' => 'Main menu',
                    'next_menu' => '__welcome',
                ],
                '2' => [
                    'display' => 'End',
                    'next_menu' => '__end',
                ],
            ],
        ],

        'say_goodbye' => [
            'message' => "Goodbye",
        ],
    ];

    public function __construct()
    {
        $this->ussd = new Kernel('default');
        $this->app->run($this);
    }

    public function before_show_date()
    {
        return ['date' => date('D-m-Y')];
    }

    public function create_date_from_format($date, $format = '')
    {
        $format = $format !== '' ? $format : $this->default_date_format;

        return DateTime::createFromFormat($format, $date);
    }

    public function is_valid_date($date)
    {
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        return checkdate($month, $day, $year);
    }

    public function validate_get_birthdate($response)
    {
        $date = $this->create_date_from_format($response);

        if ($date === false) {
            $this->app->setError('Invalid birthdate format.');
            return false;
        }

        $min = 0;
        $max = 150;
        $age = $this->calculate_age($response);

        if (!$this->is_valid_date($date) || !$this->age_within($min, $max, $age)) {
            $this->app->setError('Invalid birthdate.');
            return false;
        }

        return true;
    }

    public function age_within(int $min, int $max, int $age)
    {
        return $min <= $age && $age < $max;
    }

    public function calculate_age($birthdate, $birthdate_format = 'd/m/Y')
    {
        return DateTime::createFromFormat($birthdate_format, $birthdate)
            ->diff(new DateTime('now'))
            ->y;
    }

    public function before_show_age($user_previous_response)
    {
        $birthdate = $user_previous_response['get_birthdate'][0];

        $age = $this->calculate_age($birthdate);

        return ['age' => $age];
    }

    public function db_params()
    {
        $config = [];

        if (ENV !== 'production') {
            $config['username'] = env('DEV_DB_USER');
            $config['password'] = env('DEV_DB_PASS');
            $config['hostname'] = env('DEV_DB_HOST');
            $config['port'] = env('DEV_DB_PORT');
            $config['dbname'] = env('DEV_DB_NAME');
        } else {
            $config['username'] = env('PROD_DB_USER');
            $config['password'] = env('PROD_DB_PASS');
            $config['hostname'] = env('PROD_DB_HOST');
            $config['port'] = env('PROD_DB_PORT');
            $config['dbname'] = env('PROD_DB_NAME');
        }

        return $config;
    }

    public function appParams()
    {
        return $this->appParams;
    }

    public function menus()
    {
        return $this->menus;
    }
}

$app = new USSDApp();
