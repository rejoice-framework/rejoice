# USSD LIB

This library helps you write easily your USSD applications.

## DISCLAIMER

### 1.

This is a Work In Progress. The tutorial is been updated regularly.

### 2.

This library is made, tested and used to develop USSD applications in Ghana. It may or may not work in other country due to the fact that the authors don't know how USSD flows are handled by mobile operators in other countries (what we really want to know). Feel free to open an issue or contact the authors if you are having any issue. Feele free to contribute to the code or fork for any improvement.

## INSTALLATION

`composer require prinx/rejoice:dev-master`

## USAGE

The Library require a class that we call **Menu Manager**. The menu manager provides the menus, the menu functions, menus parameters, and database parameters to the library and run the library. The menu manager can be the controller attached to the route on which the USSD application will be available or a custom class that will be ran on the route on which the USSD application will be available.

Therefore, creating your USSD application will typically follow this schema:

- Database configuration;
- Menu construction;
- menu functions development;
- Menu parameters configuration

You create your app (the menu manager) in a class that will have as properties your database configuration, the menus, and the app parameters. The menu manager will have as methods, some functions that will handle the menus' logic.

Check `index.sample.php` for a sample application.

### The Menu manager class

Create a file called `ussd_menu_manager.php` (the name does not matter). The location of the file is up to you. Just remember you will import the file into your `index.php` or your controller (if you are using a framework).
Inside the file cerate a class.

```php
// use Prinx\RejoiceLib\Lib\UssdLib;

// The name of the class does not matter.
class USSDApp
{
  // The parameters of the application that will be passed to the USSD library
  protected $params = [];

  // A property to store the instance of the library so that you can call some
  protected $ussd;

  // Will contain the menus logic (the typical ussd flow)
  protected $menus = [];

  // Let's define the getters (or you can just make the properties public and skip the getters)

  public function params()
  {
      return $this->params;
  }

  public function menus()
  {
      return $this->menus;
  }
}
```

### Database configuration

Now let's define the database parameters.
_Why do we need some database?_
We need a database to store a user session. For Every user who dials the shortcode, a session is created. This session keep track of the user's answers to the various menus and return response to the specific user.

By providing the database parameters, the first time you run the library, a session table will be automatically created.

```php
define('ENV', 'development');
use Prinx\RejoiceLib\Lib\UssdLib;

class USSDApp
{
  // PROPERTIES
  //...

  // GETTERS
  //...

  // DB PARAMS
  public function db_params()
  {
    // It's highly recommended to use a .env file to store the database credentials
    $config = [
        'username' => '',
        'password' => '',
    ];

    if (ENV !== 'production') {
        $config['hostname'] = '';
        $config['dbname'] = '';
    } else {
        $config['hostname'] = '';
        $config['password'] = '';
        $config['port'] = '';
        $config['dbname'] = '';
    }

    return $config;
  }
}
```

We highly recommend you use a .env file to store the database credentials.
If you are not using a framework or your framework does not have a env parser, you can use the php `env` function of the library to get your env variable.

Let's create the .env file at the root of the project.
Define the parameters in the `.env` file

```env
DEV_DB_USER=root
DEV_DB_PASS=password
DEV_DB_HOST=db_ip_address
DEV_DB_PORT=3306
DEV_DB_NAME=ussd_session_db_name_or_your_app_db_name

PROD_DB_USER=root
PROD_DB_PASS=password
PROD_DB_HOST=db_ip_address
PROD_DB_PORT=3306
PROD_DB_NAME=ussd_session_db_name_or_your_app_db_name
```

The function belongs to the namespace `Prinx\Dotenv\env;`.
You can learn more on the `env()` function in this [documentation](https://github.com/Prinx/dotenv/blob/master/README.md).

```php
use function Prinx\Dotenv\env;

class USSDApp
{
  //...
  public function db_params()
  {
    // It's highly recommended to use a .env file to store the database credentials
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
}
```

### Menu construction

Let's concentrate now on the construction of the menu. This is the most important part of the application.

### Menu parameters configuration

The parameters of the application will be specified either directly in the `params` property of the Menu manager class, either inside the constructor of the menu manager class.

```php
class USSDApp
{
  //...
  public function __construct()
  {
    // The id parameter is required. It will be used to create the ussd session table. Therefore, only letters and underscore will be accepted.
    $this->params['id'] = 'first_ussd_app';
  }
}
```

The id is the only parameter that does not have a default value and therefore is required.

The other parameters (optional parameters):

```php
class USSDApp
{
  //...

  public function __construct()
  {
    $this->params['id'] = 'first_ussd_app';

    // Optional parameters
    $this->params['environment'] = 'dev';

    $this->params['end_on_user_error'] = false;

    $this->params['always_start_new_session'] = false;
    $this->params['ask_user_before_reload_last_session'] = true;

    $this->params['always_send_sms'] = true;
    $this->params['sms_sender_name'] = '';
    $this->params['sms_endpoint'] = '';

    $this->params['back_action_thrower'] = '0';
    $this->params['back_action_display'] = 'Back';

    $this->params['splitted_menu_next_thrower'] = '99';
    $this->params['splitted_menu_display'] = 'More';

    $this->params['default_end_msg'] = 'Thank you.';
    $this->params['default_error_msg'] = 'Invalid Input.';
  }
}
```

Or

```php
class USSDApp
{
  protected $params = [
    'id' => 'first_ussd_app',
    'environment' => 'dev',
    'end_on_user_error' => false,

    'always_start_new_session' => false,
    'ask_user_before_reload_last_session' => true,

    'always_send_sms' => true,
    'sms_sender_name' => '',
    'sms_endpoint' => '',

    'back_action_thrower' => '0',
    'back_action_display' => 'Back',
    'splitted_menu_next_thrower' => '99',
    'splitted_menu_display' => 'More',

    'default_end_msg' => 'Thank you.',
    'default_error_msg' => 'Invalid Input.',
  ];

  public function __construct()
  {
  }
}
```

#### `always_start_new_session`: boolean

If `true`, anytime the user dials the shortcode, the welcome menu is the menu that will be presented to her-him.
If `false`, if ever the user session times out without the user been able to complete its request or the user her-himself cancells the session, the next time the user will dial the shortcode, (s)he will be brought to the menu on which (s)he left.
**The default value is `true`**.

_Note_: If `always_start_new_session` is set to `false`, you can use the `ask_user_before_reload_last_session` parameter to control whether a prompt has to be sent to user to decide if (s)he wants to restart from where (s)he left or start from the welcome menu.

#### `ask_user_before_reload_last_session`: boolean

This parameter is true, the user will have a prompt to choose if he wants to continue from the stage where he left or start from the Welcome menu.
**The default value is `false`**.

_Note_: `ask_user_before_reload_last_session` will have effect only if `always_start_new_session` is `false`.

#### `back_action_thrower`: string or int

The input that will take the user to the previous menu. **The default value is `0` (zero).**

#### `back_action_display`

The indication to the user that (s)he can go back. **The default value is `"Back"`**.

#### `splitted_menu_next_thrower`: string or int

The input that will take the user to the following menu, if that particular menu is splitted. **The default value is `99`.**

#### `splitted_menu_display`

The indication to the user that there is another page of the same menu. **The default value is `"More"`.**

#### `default_end_msg`

Default goodbye message. It's used if no message is provided in your menu. **The default value is `"Goodbye"`.**

#### `default_error_msg`:

Default error message, if the user input is invalid. It can be modify with the `set_error` method of the library. **The default value is `"Invalid input"`.**

#### `environment`:

Must be "prod" or "dev".
Remember to modify it to "production" when in production environment. A lot of checks are bypassed to make the application faster. **The default value is `"dev"`.**

#### `end_on_user_error`: boolean

If `true`, the session will be terminated, with an error message, when the user input an invalid response. The default error message is the value of `default_error_msg`.

#### `always_send_sms`: boolean

If true, the last message displayed to the user will always be sent as sms too, provided the SMS API endpoint has been set. If not you can use your own function to send SMS.
You can, at any point, use the `send_sms` method of the library to send SMS to the user (provided the SMS API endpoint has been set).
**The default value is `false`.**

#### `sms_sender_name`

The name that will appear to the user as the SMS sender. **The default value is `""` (enpty string).**

#### `sms_endpoint`

An API endpoint to send SMS. You can decide not to use the SMS interface of the library and use your own. **The default value is `""` (empty string).**

### Running the library

In the `__construct` method:

```php
class USSDApp
{
  //...

  public function __construct()
  {
    // Application parameters...
    // ...

    $this->ussd = new USSDLib();
    $this->app->run($this);
  }
}
```

### Running the application

In your `index.php` or inside your controller (if your are using a framework), place this code.

```php
// Top of the file
require_once 'path/to/ussd/app.php';

// Place the following line inside the controller if your are using a framework.
// If not, just place it in the index.php.
$app = new USSDApp();
```

This is the minimum requirement for the ussp app to run. But often you will need to validate the response of the user, or to another stuff before or after the user sent a response, like calling an API to retrieve the balance of the user, retrieving some data from a database to display to the user, etc. Let's look at how we can do that.

### Menu functions development (Hooks)

#### `before_` functions

Three main purposes:

- feed the menu message;
- feed the menu actions
- run a specific code before the menu is shown to the user.

If you want to run a code before the menu message. The before\_ function allows you to run a code before a menu page is sent and displayed to the user. Therefore, it allows you to modify the menu message. To modify the menu message, you can either return a string, or an array of placeholders. If you return a string, the string will be what will be displayed to the user. If you return an array, the values of the array will replace the placeholders specified in your message.

So

#### `after_` functions

The ``after\_ functions are the functions that run after the user has sent a response to the application. Hence, you can validate the user response with the validate\_ function. You can do other stuff in the generic after\_ function.

##### Validate the user response

The last user response is passed to the function by the library.
This function must return a boolean: `true` if the validation passes, `false` if not.
If it returns `false`, the same menu will be run again but will have at its top an error message. The error message is the `default_error_msg` parameter. You can change the error message for a specific menu with the `set_error` function of the library.

```php
class USSDApp
{
    //...

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
}
```

You will notice we also ceated some other helper methods (calculate\*age, age_within, is_valid_date, create_date_from_format). Those methods will not be used by the library. The library will only search for the hooks (methods prefixed by `before\_`,`validate\_`,`after\_`) will be used

### Changing the default error message

Use it typically inside a validate function to define the error message that will be shown to the user if the response does not pass the validation. This function is not required. If you don't use it, the `default_error_msg` parameter will be used.

```php
class USSDApp
{
  // ...
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
}
```

### Exiting the application

You can decide to exit the application at any point with this function. Typically use it if you want to quit the application when the user sent a wrong answer. You can pass the message to display to the user if not the `default_end_msg` parameter will be used.

```php
class USSDApp
{
  // ...



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

}
```

## CODE

The .env file:

```env
DEV_DB_USER=root
DEV_DB_PASS=password
DEV_DB_HOST=db_ip_address
DEV_DB_PORT=3306
DEV_DB_NAME=ussd_session_db_name_or_your_app_db_name

PROD_DB_USER=root
PROD_DB_PASS=password
PROD_DB_HOST=db_ip_address
PROD_DB_PORT=3306
PROD_DB_NAME=ussd_session_db_name_or_your_app_db_name
```

The USSD App code:

```php
define('ENV', 'development');
require_once __DIR__ . '/vendor/prinx/rejoice/src/USSD.php';

use function Prinx\Dotenv\env;
use Prinx\Rejoice\USSD;

class USSDApp
{
    protected $default_date_format = 'd/m/Y';

    protected $appParams = [
        'id' => 'first_ussd_app',
        'environment' => 'dev',
        'end_on_user_error' => false,

        'always_start_new_session' => false,
        'ask_user_before_reload_last_session' => true,

        'always_send_sms' => false,
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
            'message' => "Of course, I'm working!"
        ],

        'show_date' => [
            'message' => 'Today is :date:!',
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
        $this->ussd = new USSD();
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
```
