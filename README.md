# THE REJOICE FRAMEWORK - DOCUMENTATION PROPOSAL
- [THE REJOICE FRAMEWORK - DOCUMENTATION PROPOSAL](#the-rejoice-framework---documentation-proposal)
  - [Introduction](#introduction)
  - [Install the framework](#install-the-framework)
  - [Configuring the application](#configuring-the-application)
    - [The `.env` file](#the-env-file)
    - [The `config/` folder](#the-config-folder)
    - [Creating the ussd menu flows](#creating-the-ussd-menu-flows)
    - [Running the simulator](#running-the-simulator)
  - [Creating a more complex application](#creating-a-more-complex-application)
    - [Creating the menu entity](#creating-the-menu-entity)
      - [The `message` method](#the-message-method)
      - [The `actions` method](#the-actions-method)
      - [The `validate` method](#the-validate-method)
      - [The `saveAs` method](#the-saveas-method)
      - [The `before` hook](#the-before-hook)
      - [The `after` hook](#the-after-hook)
      - [The `onBack` hook](#the-onback-hook)
      - [The `onMoveToNextMenu` hook](#the-onmovetonextmenu-hook)
      - [Order of the methods](#order-of-the-methods)
    - [Running the simulator](#running-the-simulator-1)
  - [Validating user's response](#validating-users-response)
    - [In the menu flow.](#in-the-menu-flow)
    - [In the menu entity](#in-the-menu-entity)
      - [USing your custom validation logic](#using-your-custom-validation-logic)
      - [Using the user response validator instance](#using-the-user-response-validator-instance)
  - [Manipulating user's response](#manipulating-users-response)
    - [Retrieve the user current response](#retrieve-the-user-current-response)
    - [Retrieve a user's previous responses](#retrieve-a-users-previous-responses)
    - [Advanced manipulations of the user's responses](#advanced-manipulations-of-the-users-responses)
  - [Magic menus](#magic-menus)
  - [Later menu](#later-menu)
  - [Calling a remote USSD application](#calling-a-remote-ussd-application)
  - [Session](#session)
  - [Retrieving the request parameters](#retrieving-the-request-parameters)
    - [The phone number of the user](#the-phone-number-of-the-user)
    - [The network (actually the mnc)](#the-network-actually-the-mnc)
    - [The session Id sent by the mobile operator](#the-session-id-sent-by-the-mobile-operator)
    - [The service code or service operator code](#the-service-code-or-service-operator-code)
    - [The USSD string](#the-ussd-string)
    - [The channel](#the-channel)
    - [Parameters defined in the `config/app.php` file](#parameters-defined-in-the-configappphp-file)
    - [Any other request POST parameters](#any-other-request-post-parameters)
    - [Any other request GET parameters](#any-other-request-get-parameters)
  - [Implementing pagination](#implementing-pagination)
    - [The `Paginator` trait](#the-paginator-trait)
    - [The paginator required methods](#the-paginator-required-methods)
      - [`PaginationFetch`](#paginationfetch)
      - [`PaginationTotal`](#paginationtotal)
      - [`itemAction`](#itemAction)
  - [Validation rules](#validation-rules)
    - [alphabetic](#alphabetic)
    - [minLength](#minlength)
    - [maxLength](#maxlength)
    - [integer](#integer)
    - [min](#min)
    - [max](#max)
    - [float](#float)
    - [amount](#amount)
    - [name](#name)
    - [age](#age)
    - [alphanumeric](#alphanumeric)
    - [date](#date)
    - [tel](#tel)
    - [regex](#regex)
    - [string](#string)
  - [Handling the last screen timeout](#handling-the-last-screen-timeout)
  - [Killing the session before running long business logic](#killing-the-session-before-running-long-business-logic)
    - [Send the response and continue the script](#send-the-response-and-continue-the-script)
    - [Send the response and terminate the script](#send-the-response-and-terminate-the-script)
  - [Sending SMS](#sending-sms)
    - [Send and exit the script](#send-and-exit-the-script)
  - [Logging](#logging)
  - [Inserting defined actions](#inserting-defined-actions)
    - [Inserting Welcome actions](#inserting-welcome-actions)
    - [Inserting Back actions](#inserting-back-actions)
    - [Inserting Paginate forward actions](#inserting-paginate-forward-actions)
    - [Inserting Paginate back actions](#inserting-paginate-back-actions)


## Introduction
Rejoice is a PHP framework dedicated for USSD applications. It aims to be a framework that can speed up your USSD application creation. The main purpose of the framework is USSD applications but it can create any application that is USSD-like, like a whatsapp automatic chat bot.

A USSD application is basically a series of popups (which we call *menus* or *pages* or *screens*) that display or request information from a user via their mobile phones. Each interaction of a user in a USSD application is called session.

The Rejoice Framework allows you to create simple to complex USSD applications by allowing you to create each menu and have control over everything from what is displayed to controling the users inputs.

## Install the framework
```shell
~/www$ mkdir rejoice-first-project
~/www$ cd rejoice-first-project
```
Then download the project files:
```shell
~/www/rejoice-first-project$ composer create-project prinx/rejoice
```

## Configuring the application
### The `.env` file
Open the .env file just to see the variables inside. The only variable we are going to modify is the `APP_URL`. It will be used by the simulator. It is not required though.

```ini
APP_ENV=dev
APP_URL=http://localhost/rejoice-first-project/

# Not required
USSD_CODE=
SMS_ENDPOINT=

# Can be "file" or "database"
SESSION_DRIVER=file

# Not needed, as sessions will be stored in files
SESSION_DB_USER=root
SESSION_DB_PASS=
SESSION_DB_HOST=localhost
SESSION_DB_PORT=3306
SESSION_DB_NAME=

# We are not going to use any database yet
APP_DEFAULT_DB_USER=root
APP_DEFAULT_DB_PASS=
APP_DEFAULT_DB_HOST=localhost
APP_DEFAULT_DB_PORT=3306
APP_DEFAULT_DB_NAME=
```
### The `config/` folder

The configurations of the application are in the `config/` folder. The `config/app.php` allows you to configure some parameter of the application itself.

Go to `config/app.php` to see what is inside. The default values are of for our first application.

The `config/database.php` allows you to configure the databases used by the application.

The `config/session.php` allows you to configure the session driver and each driver details. You will configure there the session database if you will to use a database to store your sessions.

The `config/rejoice.php`  is reserved for the framework itself. You will usually not use it. But it can be useful if you need to change some lookup path that the framework uses.

Some of the configurations, especially the ones related to credentials can be configured from the .env file at the root of the application. You can actually use the `env()` function to require any environment variable in the configuration files.

### Creating the ussd menu flows
The menus that will be displayed are defined in the `app/Menus/DefaultMenus/menus.php` file. It is actually an associative array containing the menus. Each menu is identified by a name (each index of the array). The name will be used when calling the menu to be displayed and also to create the class that will be responsible of the business logic of the menu (the menu entity). It is recommended to use a meaningful name, in lowercase with the word separated with undescore.
*The only naming rule is: the menu name must not start with a number.*

These are valid menu names:
'get_age_screen', 'user_choice', 'userChoice', 'UserChoice', 'user-choice', 'user1_choice' ..., as long as it does not start with a number.

> ☝️ **Info**
> The rule on the menu name comes from the fact that we will be defining classes derived from the menus' names (those classes are called *menu entities*).

A menu screen is composed of two main parts: the **message** and the **actions**.

Any menu without action will be the last menu displayed to the user, as the user can no more input something. The session will then be ended.

The array returned in the `app/Menus/DefaultMenus/menus.php` file must contain `welcome` menu. The welcome menu is the only menu required.

Based on these information, let's go to  `app/Menus/DefaultMenus/menus.php` and write our first menu.
<!-- 
```php
// app/Menus/DefaultMenus/menus.php

return [
  
  // The welcome menu
  'welcome' => [
    'message' => "Welcome to your first menu\nSelect an option",
    
    'actions' => [
      '1' => [ // If user selects 1
        'display' => 'Get inspiring quote',
        'next_menu' => 'inspiring_quote' // Go to inspiring_quote screen
      ],
      // '2' => [
      //   'display' => 'Get services prices',
      //   'next_menu' => 'services_list'
      // ],
    ]
  ],

  'inspiring_quotes' => [
    'message' => "The quote today is 'To be or not to be, that is the question! - Shakespeare'",
  ],

  // 'services_list' => [
  //   'message' => 'Choose a service to get its price:',
  //   'actions' => [
  //     '1' => [
  //       'display' => 'Day-by-day',
  //       'next_menu' => 'day_by_day_pack',
  //     ],
  //     '2' => [
  //       'display' => 'All time pack',
  //       'next_menu' => 'all_time_pack',
  //     ],
  //     '3' => [
  //       'display' => 'Seasoned pack',
  //       'next_menu' => 'seasoned_pack',
  //     ],
  //   ]
  // ],
];
``` -->

```php
// app/Menus/DefaultMenus/menus.php

return [
  
  // The welcome menu
  'welcome' => [
    'message' => "Welcome to your first menu\nSelect an option",
    
    'actions' => [
      '1' => [ // If user selects 1
        'display' => 'Get inspiring quote',
        'next_menu' => 'inspiring_quote' // Go to inspiring_quote screen
      ],
    ]
  ],

  'inspiring_quotes' => [
    'message' => "The quote today is 'To be or not to be, that is the question! - Shakespeare'",
  ],
];
```

That's all! You have your first USSD application!

But before we go to the simulation board, let's explain what is happening.
First any menu has to have a welcome screen. Here our welcome screen has a message:
```shell
Welcome to your first menu
Select an option
```
and only one action:
```shell
1. Get inspiring quotes
```

The menu will then look like this on the phone:
```shell
Welcome to your first menu
Select an option

1. Get inspiring quote
```
| ******
 ------------------------------------------------------------------------|
| <br><small>Welcome to your first menu<br>Select an option<br>1. Get inspiring quote<br></small>|
| \|...                                                                      |
| 1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3<br>4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6<br>7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9<br>*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#                  |

In the action array, we map the character `1` to the *next_menu* `inspiring_quote`. It simply means, if the user responds `1` to the welcome menu, the inspiring_quote menu will be called and displayed. That is the main thing we need to know to be able to create all the menu that we want with any complex dynamism. **We match a user input to a menu to call.**

Let's run our application in a ussd simulator to show what it will look like in the real life.

### Running the simulator
Rejoice comes with a default console command called `smile` :)
To run the simulator, run this command in the console, at the root of our project:
```console
~/www/rejoice-first-project$ php smile simulator run
```
You will get the following response:
```console
Server started at http://127.0.0.1:8000
Press Ctrl+C to exit.
[Sat Jun 27 YYYY 20:42:48] PHP 7.4.5 Development Server (http://127.0.0.1:8000) started
```
<small>The date and PHP version will be the ones of your environment at the time you are running the command.</small>
Now you can open your browser at URL http://127.0.0.1:8000. If you have well configured the APP_URL in the .env file, everything must be OK. If not, you will need to input the URL of the project in the `endpoint` input. Now, input the phone number you will to use in the `Phone Number` input. Everything is ok. Hit the `Dial` button. verything will be the same as on phone.

## Creating a more complex application
We've created our first application and that was pretty cool and rather simple. I'm sure you will need more in your applications.
LNowthat we have all the basics, the rest will not be a big deal.

The first thing that worth knowing is Menu Entities.
A menu entity is an object representaion of a menu. The same menu we created in the `menus.php` file above. We will now create it using rather an object. Actually, the menu entity does not replace the menu defined in the `menus.php` file. Both can coexist. But the parameters defined in the menu entity will for most of them overwrite the ones defined in the menus.php file.

A menu entity inherit from a base menu entity class.

The basic scheme looks like this:

```php
namespace App\Menus;

use App\Rejoice\Menu;

class InspiringQuote extends Menu
{
  /**
     * Returns the message to display before the actions on the current menu
     *
     * @param \Prinx\Rejoice\UserResponse $userPreviousResponses
     * @return string|array
     */
    public function message($userPreviousResponses)
    {
        return '';
    }

    /**
     * Returns the actions of the current menu
     *
     * @param \Prinx\Rejoice\UserResponse $userPreviousResponses
     * @return array
     */
    public function actions($userPreviousResponses)
    {
        return [];
    }

    /**
     * Allow you to validate the user's response.
     *
     * If it returns false, an invalid input error will be send to the user.
     *
     * @param string $response
     * @return boolean
     */
    public function validate($response)
    {
        return true;
    }

    /**
     * Allow you to modify the user's response before saving it in the session.
     *
     * @param string $response
     * @return mixed
     */
    public function saveAs($response)
    {
        return $response;
    }

    /**
     * `before` hook. Runs before any other method in the current menu.
     * You will usually retrieve data to display in database here from database here
     * @param \Prinx\Rejoice\UserResponse $userPreviousResponses
     * @return void
     */
    public function before($userPreviousResponses)
    {
        //
    }

    /**
     * `after` hook. Runs after the user has sent their response.
     * This method runs anytime we move from the current menu to another menu.
     * It means even when we move from this menu to go back, it will run.
     * Sometimes it is useful, Sometimes that is not the behavior we want. 
     * We rather want a method to run a method only when we are moving to a 
     * real next_menu, not a previous menu or the welcome menu. In that 
     * particular case, we will use the `onMoveToNextMenu` method.
     * 
     * @param string $response The response given by the user on this menu screen
     * @param \Prinx\Rejoice\UserResponse $userPreviousResponses All the previous responses given by the user, attached to their menu name.
     * @return void
     */
    public function after($response, $userPreviousResponses)
    {
        //
    }

    /**
     * Similar to the after method but will run only when moving from this menu 
     * to a real next_menu. We call a real next_menu, a menu that has been 
     * defined by you. It then excludes menus like `__back`, `__welcome`, 
     * `__same`, `__paginate_forward`, `__paginate_back`, which are 
     * automagically created and managed by the framework.
     * 
     * @param string $response
     * @param \Prinx\Rejoice\UserResponse $userPreviousResponses
     * @return void
     */
    public function onMoveToNextMenu($response, $userPreviousResponses)
    {
      //
    }

    /**
     * Method to run when moving back.
     * 
     * @param \Prinx\Rejoice\UserResponse $userPreviousResponses
     * @return void
     */
    public function onBack($userPreviousResponses)
    {
      //
    }
}

```
All these methods are optional. Use the one you want when you need it.

### Creating the menu entity 

#### The `message` method

#### The `actions` method

#### The `validate` method

#### The `saveAs` method

#### The `before` hook

#### The `after` hook

#### The `onBack` hook

#### The `onMoveToNextMenu` hook

#### Order of the methods
While writing your menu entity logic, it is important to know the order in which the menu entity methods are called.
This is the order in which the menu entity methods are called.

Methods called before the menu is rendered to the user:
- `before`
- `message`
- `actions`

After the menu is rendered to the user **and the user has sent a response back**:
- `validate`
- `saveAs`
- `after`
- `onMoveToNextMenu`


Knowing the order of the menu entity methods helps you to know how to handle your own-created menu entity properties.

<div class="note note-primary">
You need to consider this:

Any property of the menu entity is accessible in any of the menu entity methods.

Any property created or updated in a `before` method (before, message or actions) will not be reflect in an `after` method (validate, saveAs, after, onMoveToNextMenu, etc.)

Any property created or updated in a particular method will not be updated get the update in a method that has already run, according to the order of the methods (for example, an update of a property in the after method will not reflect in the validate method, because at the time the after method is running, the validate method has already run).
</div>

### Running the simulator
Rejoice comes with a default console command called `smile` :)
Run the smile command with the arguments `simulator` to run the simulator.

In the console
```console
$ php smile simulator run
```

## Validating user's response
Rejoice has made it very easy to validate the user sent on each particular menu.
You can validate directly inside the menu flow in the `menu.php` file or validate in the menu entity.

### In the menu flow.

```php
// menus.php
return [
  //

  'enter_age' => [
    'message' => 'Enter your age'
    'actions'=> [
      '0' => [
        'display' => 'Back',
        'next_menu' => '__back',
      ]
    ]

    'default_next_menu' => 'retrieve_birth_year'
    'validate' => 'integer|min:5|max:150'
  ]
];
```
On the previous example, if the user input `0`, they will go back, if not any other input will be validated against the rules specified in the `validate` parameter of the menu.

<div style="">
<strong>Note</strong><br>
Whatever action is specified in the `actions` parameter has the priority over the any other input the user may send.*

<br>Those values are not checked in the validating process because the are self-validating, meaning the user has to input them exactly as they are. Only the values that the developer does not have a control over, will be validated (like the name of the user; the user can send a number at the place of their name.)</div>

Check the index of the validation rules [here]().


### In the menu entity
#### USing your custom validation logic
```php

class EnterAge extends Menu
{
  //
  public function message($userPreviousResponses)
  {
      return '';
  }

  public function validate($response)
  {
      $age = intval($response);

      if ($age < 5 || $age > 150) {
        return false;
      }
      
      return true;
  }
}
```

You can modify the default error message:
```php

class EnterAge extends Menu
{
  //

  public function validate($response)
  {
      $age = intval($response);

      // You can also use $this->setError()
      if ($age < 5) {
        $this->addError('You must be more than 5 years to continue');
        return false;
      } elseif ($age > 150) {
        $this->addError('You must be less than 150 years to continue');
        return false;
      }
      
      return true;
  }
}
```

A more advisable way to validate will be as follow because an error can also be defined somewhere else by the framework itself (especially if you specify a `validate` parameter for the same menu in the `menus.php` file):
```php

class EnterAge extends Menu
{
  //

  public function validate($response)
  {
      $age = intval($response);

      if ($age < 5) {
        $this->addError('You must be more than 5 years to continue');
      } elseif ($age > 150) {
        $this->addError('You must be less than 150 years to continue');
      }
      
      return empty($this->error());
  }
}
```    
#### Using the user response validator instance
The user response validator is actually the class that the framework uses to automatically validate the response when you specify the validation rules in the `menus.php`. You can also use the same class inside your `validate` method:
```php
// Import the class from its namespace
use Prinx\Rejoice\UserResponseValidator as Validator;

class EnterAge extends Menu
{
  //

  public function validate($response)
  {
      $rules = 'integer|min:5|max:150';
      return Validator::validate($response, $rules);
  }
}
``` 
Or with the custom error messages:
```php
// Import the class from its namespace
use Prinx\Rejoice\UserResponseValidator as Validator;

class EnterAge extends Menu
{
  //

  public function validate($response)
  {
      $rules = [
          'integer',
          ['min:5', 'You must be more than 5 years to continue'],
          ['max:150', 'You must be less than 150 years to continue'],
      ];

      return Validator::validate($response, $rules);
  }
}
```
Or
```php
// Import the class from its namespace
use Prinx\Rejoice\UserResponseValidator as Validator;

class EnterAge extends Menu
{
  //

  public function validate($response)
  {
      $rules = [
          'integer',
          [
              'rule' => 'min:5',
              'error' => 'You must be more than 5 years to continue'
          ],
          [
              'rule' => 'max:150',
              'error' => 'You must be less than 150 years to continue'
          ],
      ];

      return Validator::validate($response, $rules);
  }
}
```
Or
```php
// Import the class from its namespace
use Prinx\Rejoice\UserResponseValidator as Validator;

class EnterAge extends Menu
{
  //

  public function validate($response)
  {
      $rules = [
          'integer',
          'min:5' => 'You must be more than 5 years to continue',
          'max:150' => 'You must be less than 150 years to continue',
      ];

      return Validator::validate($response, $rules);
  }
}
```
Or We can be less verbose by directly returning only the rules:
```php
// No need to import the class the validator class

class EnterAge extends Menu
{
  //

  public function validate($response)
  {
      return [
          'integer',
          'min:5' => 'You must be more than 5 years to continue',
          'max:150' => 'You must be less than 150 years to continue',
      ];
  }
}
```
<div class="note note-warning">
An empty validation rule will throw a `RuntimeException`.</div>

## Manipulating user's response
### Retrieve the user current response
In a menu entity you can easily access the current user's response using the method `userResponse`.

If you have modified the response by using the `saveAs` method or the `save_as` parameter, you can access the modified response by calling the method `userSavedResponse`. The userResponse method or userTrueResponse will always give the true response that the user gave will userSavedResponse will give the modified response if the response has been modified or the true response if the response has not been modified. 

```php
class EnterAge extends Menu
{
    public function message()
    {
        // We suppose the user has entered their name on the previous menu
        $name = $this->userResponse();
        return "Cool {$name}! Kindly enter your age:";
    }
}
```

The user response is automatically injected into the `validate` and `saveAs` methods.

```php
// Notice this is the enter name menu and not enter age as the previous one - A response is validated on its menu not another menu.
class EnterName extends Menu
{
    public function validate($userResponse)
    {
        if (strlen($userResponse) < 3) {
            $this->setError('The name must be at least 3 characters');

            return false;
        }

        return true;
    }
}
```

<div class="note note-warning">It is important to always see a menu entity in two parts: <strong>the part that runs before the screen is rendered</strong> to the user and <strong>the part that runs after the user has sent the response</strong>.

Then when you request for the user response in the <em>before-rendering</em> part, you are actually requesting the response giving on the previous screen and based on that response you want to write your logic to render the screen for the current menu. When you request for the user response in the <em>after-response</em> part, you are requesting the response given on the current menu, maybe for validation purpose or to modify the response before saving it, etc.</div>

### Retrieve a user's previous responses
Anywhere in a menu entity, you can access previous responses of the user by using the method `userPreviousResponses` of the menu entity.
The method returns a UserPreviousResponse object that holds all the previous responses of the user and allows you to access. The object returned has two main methods that allows you to access the responses: `get` and `has`. Both methods take as parameter the name of the menu for which you want to retrieve the response.
`has` as you can guess, checks if there is a response for this particular menu.
`get` gets the response for this particular menu.

```php
class EnterAge extends Menu
{
    //

    public function validate($response)
    {
        if ($this->userPreviousResponses()->has('has_a_permit')) {
            return true;
        }
        
        $age = intval($response);
        $wantToDrive = $this->userPreviousResponses()->get('want_to_drive');
        
        if ($wantToDrive && $age > 16) {
            $this->setError('Only adults are allowed to drive.');
            return false;
        }

        return true;
    }
}
```
Some methods of the menu entity get automatically the userPreviousResponses object. as argument. You can decide to use them as argument or stick to the method. Both will give the same result.
The methods `before`, `after`, ``,
Calling `get` on a non existent menu may will throw an Exception.

### Advanced manipulations of the user's responses
Let's suppose you are creating an application in which users can place orders. You want to allow the users to place multiple orders during the same session on the USSD, you need a way of remembering all the previous responses of the user. The framework allows you to implement such logic very easily.

Actually anytime you come to a screen, the response provided by the user is saved in a responses array for that particular menu. You can then get all the responses provided for a same particular menu by calling the `getAll` method on the object returned by the `userPreviousResponses` method.

```php
class ProcessOrder extends Menu
{
    public function before($userPreviousResponses)
    {
        $definedPlateCosts = [
            'beans' => 7,
            'fufu' => 12,
            'jollof' => 10,
            'spaghetti' => 9,
        ];

        // Returns for example ['jollof', 'beans']
        $orderedMeals = $userPreviousResponses->getAll('choose_meal');

        // Returns for example [2, 5]
        $plateNumbers = $userPreviousResponses->getAll('enter_number_plate');

        $cost = 0;
        foreach ($orderedMeals as $key => $meal) {
            $cost += $definedPlateCosts[$meal] * $plateNumbers[$key];
        }

        $message = 'You have ordered ' . implode(',', $orderedMeals) . 
        '. The total cost is '.$cost.
        '. Your request is been processed.';

        $this->terminate($message)
    }
}
```
You can get a specific response by passing as second parameter to the `get` method the index of the response:
```php
class ProcessOrder extends Menu
{
    public function before($userPreviousResponses)
    {
        $secondOrderedMeal = $userPreviousResponses->get('choose_meal', 1); // Yes 1. Remember we count from 0 :)
        $this->terminate('You second meal is ' $secondOrderedMeal)
    }
}
```
## Magic menus
Rejoice offers the possibility of navigating easily from a menu to another especially when it comes to doing things like going back or returning to the welcome menu, etc. Those menus are called magic menus. Their names starts with double undescore. They are automatically configure on any application. These are all the magic menus that Rejoice ships with.
- `__back` for going back.
- `__welcome` for returning to the main menu
- `__end` to return a last menu message (a.k.a end the session)
- `__same` for displaying the same menu again
- `__paginate_forward` for implementing a forward pagination (this is used by the [Paginator](#implementing-pagination))
- `__paginate_back` for implementing a back pagination (this is used by the [Paginator](#implementing-pagination))

Those are the magic menus you can use directly in your application to navigate. There are other magic menus that are reserved to the framework:
- `__split_next` When a menu overflows, Rejoice automatically detects it and splits it under the hood for you. `__split_next` is the name that will be used to reference any next screen of the splitted menu.
- `__split_back` will be used to reference any back screen of a splitted menu.
- `__continue_last_session` The name of the *continue from last session* menu.


<div class="note note-danger">It is highly recommended not to name any of your own menu with double underscore at the beginning!</div>

## Later menu
Later menu is a powerful concept that helps you to modify the normal flow of the menu. Actually, every menu defined is attached to another menu through the `next_menu` parameter. It restrict you to use a menu for a specific purpose out of it flow. The later menu bypass that limitation and allows you to call a menu outside of its flow. View from other side, it allows us to create a menu that is not dependent on a particular flow but is rather *alone* and used by multiple flows. This is a scenario to help understand the concept and a use case of a later menu.
```php
//
    'choose_option_screen' => [
        'message' => 'Select an option'
        'actions'=> [
            '1' => [
              'display' => 'Get balance',
              'next_menu' => '',
              'later' => '',
            ]
        ]
    ]

```

The later menu can be either an array or a string.
If an array, the menus inside the array will be called one after the other till the end of the array. It's important to know that a later menu has priority on the normal flow. Whenever the later menu stack is not empty, Rejoice will pick the next menu from the later menu stack till the stack is empty before returning to the normal flow.


## Calling a remote USSD application
You can call a remote USSD simply by setting the URL of the USSD application as *next_menu* to an action.
```php
// menus.php
return [
    //...

    'choose_option_screen' => [
        'message' => 'Select an option'
        'actions'=> [
            '1' => [
              'display' => 'Get balance',
              'next_menu' => 'http://address.ip/or/url/to/the/remote/ussd',
            ]
        ]
    ]

    //...
];
```
<div class="note note-warning">
It's important to know that as soon as the USSD has switched to a remote USSD, all subsequent requests will be routed to that remote USSD and this current USSD will be just a relay. No menu of the current USSD can be called again. Everything happens as if the current USSD application has completely handed over to the remote USSD.</div>

## Session
A session is created anytime a user access the application.

You can easily save a variable in the session by using the `sessionSave` method.
You can easily retrieve a variable from the session by using the `sessionGet` or its shortcut `session` method.
You can easily check if a variable is in the session by using the `sessionHas` method.
You can easily remove a variable from the session by using the `sessionRemove` method.
```php

class EnterAge extends Menu
{
  //
  public function message($userPreviousResponses)
  {
      if ($this->sessionHas('a_variable')) {
        return $this->sessionGet('a_variable')
      }

      $aVar = 2;
      $this->sessionSave('a_variable', $aVar);
      return $aVar;
  }
}
```

The `session` and `sessionGet` methods support a default value parameter, in case the parameter specify does not exist in the session.
```php
class EnterAge extends Menu
{
  //
  public function message($userPreviousResponses)
  {
      // Return the session value a_variable or 42 if a_variable is not in session
      return $this->session('a_variable', 42);
  }
}
```
<div class="note note-warning">
If the value is not found in session and no default value passed, a RuntimeException is thrown.
</div>

## Retrieving the request parameters
### The phone number of the user
```php
$this->tel();
// or
$this->msisdn();
```

### The network (actually the mnc)
```php
$this->network();
```

### The session Id sent by the mobile operator
```php
$this->sessionId();
```

<div class="note note-warning">WARNING:

```php
$this->session('id');
```
will NOT work.
</div>

### The service code or service operator code
```php
$this->ussdRequestType();
```

### The USSD string
```php
$this->userResponse();
```

### The channel
Useful when building an application not only for USSD. You can do extra stuff, customize the response, etc., based on the channel.

```php
$this->channel(); // Will be USSD by default.
```
### Parameters defined in the `config/app.php` file
```php
$this->config('app.param_name'); // Will be USSD by default.
```

### Any other request POST parameters
```php
$this->request()->input('name_of_the_input');
```
For post parameters, the request method can be used directly:
```php
$this->request('name_of_the_input');
// Equivalent to
$this->request()->input('name_of_the_input');
```

### Any other request GET parameters
```php
$this->request()->query('name_of_the_input');
```

## Implementing pagination
### The `Paginator` trait
To implement pagination, you will probably need to use the paginator trait. It allows you to quickly deploy a paginable menu.

Note: The `Rejoice` framework handles two types of pagination.
The first one that we call `soft-pagination` is when all the data is retrieved and passed to the framework. It works fine when the data to paginate is small.
The second type of pagination called the `hard-pagination` is the one that requires the use of the first one is when a menu overflows

### The paginator required methods
#### `PaginationFetch`
Retrieve the data from the database. Must return the retrieved data.

#### `PaginationCountAll`
Must Return the total of rows fetched from the database.

#### `itemAction`
When using the paginator trait, instead of the `actions` methods, you will use the `itemAction` method to specify how to insert the actions in your menu. This method will be used under the hood by the actions method to insert the actions automatically for you. 
**Example**
```php
namespace App\Menus;

use Prinx\Rejoice\Menu\Paginator;

class BetsHistory extends Menu
{
    use Paginator;

    /**
     * Number of items to display per page
     */
    protected $maxItemsPerPage = 4;

    public function message()
    {
        if (!$this->paginationTotal()) {
            return "You don't have any bet.";
        } else {
            return $this->isPaginationFirstPage() ? 'Your betting history' : '';
        }
    }

  /**
   * Defines how the items will be displayed to the user.
   * 
   * This method will automatically be called for each rows of the array 
   * returned by `paginationFetch`. And its return value will be added to the 
   * actions 
   * 
   * The option is what will be displayed to the user as option to select.
   * It's automatically handled by the Paginator
   * 
   * @param array $row 
   * @param string $option
   * @return array
   */
    public function itemAction($row, $option)
    {
        return [
            $option => [
                'message' => 'Bet ' . $row['id'],
                'next_menu' => 'bet::bet_details',
                'save_as' => $row['id'],
            ],
        ];
    }

    /**
     * Fetches the items from the database
     * 
     * @return array
     */
    public function paginationFetch()
    {
        if (isset($this->bets)) {
            return $this->bets;
        }

        $req = $this->db()->prepare("SELECT * FROM bets
        WHERE id > :offset
        AND initiator_id = :initiator_id
        ORDER BY id
        LIMIT :limit");

        $offset = $this->lastRetrievedId();
        $userId = $this->user('id');
        $limit = $this->maxItemsPerPage();

        $req->bindParam('offset', $offset, \PDO::PARAM_INT);
        $req->bindParam('initiator_id', $userId);
        $req->bindParam('limit', $limit, \PDO::PARAM_INT);
        $req->execute();

        $this->bets = $req->fetchAll(\PDO::FETCH_ASSOC);

        $req->closeCursor();

        return $this->bets;
    }

    /**
     * Returns the total number of the data to be displayed
     * 
     * @return  int
     */
    public function paginationCountAll()
    {
        if ($total = $this->paginationGet('total')) {
            return $total;
        }

        $req = $this->db()->prepare("SELECT COUNT(*) FROM bets WHERE  initiator_id = ?");
        $req->execute([
            $this->user('id')
        ]);

        $paginationTotal = intval($req->fetchColumn());
        $req->closeCursor();

        $this->paginationSave('total', $paginationTotal);

        return $paginationTotal;
    }
}
```
The paginator makes available some methods for our use:
- `maxItemsPerPage` The maximum number of items that can be showed on the pagination screen; It's configured as protected property of the menu entity;
- `currentItemsCount` The actual number of items showed on the current screen;
- `isPaginationFirstPage` Check if the current screen is the first screen of the pagination;
- `isPaginationLastPage` Check if the current screen is the last screen of the pagination;
- `lastRetrievedId` Get the id of the last fetched row - the next query to the database to fetch, will begin at that index;
- `paginationSave` Saves a data for pagination purpose;
- `paginationGet` Get a pagination data;
- `paginationHas` Check if a pagination data exists;

<div class="note note-warning">When using the pagination, always prefer 

```php
$this->userPreviousResponses('the_name_of_the_previous_menu');
```
to retrieve the response of the previous menu, over

```php
$this->userResponse(); 
//or
$this->userSavedResponse();
```
</div>

## Validation rules
Some rules accept parameters. You specify the parameter by following the rule with **colon** (`:`) and the value of the parameter.

In the case a rule requires multiple variables, you can pass the variables by seperating them with a **comma** (`,`). This means, **you cannot pass a colon or a comma as argument to a rule**. If you are required to pass a colon or a comma as argument, you must create a custom validation inside the menu entity.

You can combine several rules by separating them with a pipe (`|`).
These are the default rules you can use:

<div class="note note-info">The rules can be camelCase, snake_case, PascalCase or even kebab-case. For example, you can use 'maxLength', 'max_length', 'MaxLength' or even 'max-length'.</div>


### alphabetic
Check if the string contains only letters

```php
return [

  'enter_name' => [
        'message' => 'Enter your name'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'alpha'
        // or
        // 'validate' => 'alphabetic'
    ]
];
```


### minLength
Takes the minimum length required as parameter.

```php
return [

  'enter_name' => [
        'message' => 'Enter your name'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'alpha|minLength:3'
    ]
];
```

<div class="note note-info">You can use `minLength` or `minLen`.</div>

### maxLength
Takes the maximum length required as parameter.

```php
return [

  'enter_name' => [
        'message' => 'Enter your name'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'alpha|maxLength:30'
    ]
];
```
<div class="note note-info">You can use `maxLength`, `maxLen`.</div>

### integer
```php
return [

  'enter_age' => [
        'message' => 'Enter your age'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'integer'
    ]
];
```

### min
```php
return [

  'enter_age' => [
        'message' => 'Enter your age'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'integer|min:10'
    ]
];
```
### max
```php
return [

  'enter_age' => [
        'message' => 'Enter your age'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'integer|max:50'
    ]
];
```

### float
```php
return [

  'enter_length' => [
        'message' => 'Enter the desired length'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'float'
    ]
];
```
<div class="note note-info">The rules `min` and `max` also work for float values.</div>

```php
return [

  'enter_length' => [
        'message' => 'Enter the desired length'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'float|min:1.55|max:70.95'
    ]
];
```
### amount
```php
return [

  'choose_option_screen' => [
        'message' => 'Select an option'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'amount'
    ]
];
```
The validation `amount` is just a compilation of `float|min:0`.

### name
```php
return [

  'enter_name' => [
        'message' => 'Enter your name'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'name'
    ]
];
```
The validation `name` is a compilation of `alpha|min_len:3|max_len:50`.

### age
```php
return [

  'enter_age' => [
        'message' => 'Enter your age'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'age'
    ]
];
```
The validation `age` is a compilation of `integer|min:0|max:100`.

### alphanumeric
```php
return [

  'choose_option_screen' => [
        'message' => 'Select an option'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'alphanum'
        // or
        // 'validate' => 'alphanumeric'
    ]
];
```
<div class="note note-warning">To validate alphanumeric, the response is validate against this regular expression: /(\W?\w)+/</div>

### date
The date validation takes the format of the date as argument. If no format is passed, the default format `j/n/Y` is used.

```php
return [

  'enter_date' => [
        'message' => 'Enter your date of birth'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'date:d/m/Y'
    ]
];
```
<div class="note note-info">The format must be a valid PHP date format.</div>
<div class="note note-info">The default format (j/n/Y) accepts dates like '1/5/2025', '07/6/2030', etc. (The date and month do not need the '0' at their begining.)</div>
<div class="note note-info">In case you need to perform some calculation on the date, like check if the date is too old or too far from another date, you can use the methods in the Date Utilities class to perform the validation inside the menu entity.</div>

### tel
Checks if the response is a valid telephone number.

```php
return [

  'enter_date' => [
        'message' => 'Enter your date of birth'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'date:d/m/Y'
    ]
];
```
<small>Due to the various way a telephone number can be specified, this does not check if the phone number is a real phone number. But rather just check it against the general format of phone numbers, meaning the number can start with the '+' sign or '00', must contain only digits, parenthesis, spaces or hyphens, must be longer than 7 digits and shorter than 15 digits. You can use the `internationaliseNumber` method of the `Str` utils class to format the number to the specific country's phone number pattern.</small>'

### regex
You can use a regular expression to define your own rule.
```php
return [

  'enter_name' => [
        'message' => 'Enter your name starting by with your title (Mrs/Mr)'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'regex:/^Mrs? [a-z]{3,50}/i'
    ]
];
```

The regex must be enclosed in delimiters (`/` in the regex above). It allows you to easily add flags (notice the case-insensitive flag `i` in the regex above)

### string
Check if the response is a string. It is the validation that is applied by default. When using the framework for a USSD application, there is no need to specify this validation. It is done automatically by the framework. It will become iseful when using the framwork outside the scope of a USSD application.

```php

return [

  'enter_name' => [
        'message' => 'Enter your name'
        'actions'=> [
            '0' => [
              'display' => 'Back',
              'next_menu' => '__back',
            ]
        ]

        'validate' => 'string'
   ],
];
```

## Handling the last screen timeout
<div class="note note-info">This section applies only to the last message sent to the user by your application</div>

By default, the framework compiles the screen and send it to the user's phone based on the configured `message` and `actions` (both in the menus.php and in the menu entity). But sometimes, the last screen does not show up and the user rather gets a connection MMI error. The usual workaraound is to set the `allow_timeout` parameter to `false` in the `config/app.php` file. This result of this will be, on the last screen, the user will still have the possiblilty to input a response (even though that response will not have any meaning). This is just to make sure the user see the last response no matter how long is the USSD flow. workaround you need to kill the session by yourself to be able to do extra-stuff after the user has received the response on his screen. You can perform operation on database and all the things that take a time after you have sent the response to the user.

## Killing the session before running long business logic
<div class="note note-info">This section applies only to the last message sent to the user by your application</div>

Sometimes, it is uselful, if not required to send the response to the user while continuing a business logic, either because the USSD can timeout or because it is required to be able to call another push request to the user's phone, or calling an API that will take long to respond, etc. In those cases, we will just need to call some functions to send a response to the user, while continuing our business logic.

### Send the response and continue the script
```php
class ProcessBalanceRequest extends Menu
{
  public function before($userPreviousResponses)
  {
    $this->respond('Your request is been processed')
    
    // Continue the script (insert in database, call an API, etc.)
  }
}
```
Instead of using the `respond` method, you can use:
```php
// Same as `respond`
$this->respondAndContinue('Your request is been processed')
```
or
```php
// Same as `respond`
$this->softEnd('Your request is been processed')
```
All the three methods (respond, respondAndContinue, softEnd) are doing the exact same thing. Just choose the one you like.

### Send the response and terminate the script
```php
class ProcessBalanceRequest extends Menu
{
  public function before($userPreviousResponses)
  {
    // Your business logic here

    // Sends the response and terminate the script automatically
    $this->terminate('Your request is been processed'); 
    
    // Anything here will not run
  }
}
```

Instead of using the `terminate` method, you can use:

```php
// Same as `terminate`
$this->respondAndExit('Your request is been processed')
```
or
```php
// Same as `terminate`
$this->hardEnd('Your request is been processed')
```
All the three methods (terminate, respondAndExit, hardEnd) are doing the exact same thing. Just choose the one you like.


## Sending SMS
```php
// Menu entity
$this->sendSms('Your request is been processed')
```
### Send and exit the script
```php
// Menu entity
$this->sendSmsAndExit('Your request is been processed')
```
The two methods accept two other parameters that are in order:
- The sender name
- the sms endpoint.

```php
// Menu entity
$this->sendSmsAndExit('Your request is been processed')
```

But you can also configure the sender's name and the sms endpoint in the .env file.
```ini
SMS_ENDPOINT=https://...
SMS_SENDER_NAME=MYAPP
```
<div class="note note-info">
Even though you can call those methods anywhere in your menu entity, the proper methods for calling them are the `before`, the `after`, `onBack`, `onMoveToNextMenu`, `onPaginateBack` or `onPaginateForward` methods. Those methods are generic and allow you to do your special stuff while the other methods are more purposed.
</div>

## Logging
```php
// Menu entity
$this->log('Balance sent')
```
The log will be stored in the `storage/logs/{date}/{name_of_the_menu_that_is_logging}.log` file.

## Inserting predefined actions
The available predefined actions are:
- backAction
- mainMenuAction
- paginateForwardAction
- paginateBackAction

### Inserting Back action
You can add a go-back action to your actions by using the `__back` next menu or by calling the `$this->backAction()` method.

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
      
      // Add back action
      '0' => [
        'display' => 'Back',
        'next_menu' => '__back',
      ]
    ];

    return $actions;
  }
}
```
The trigger is `0` and the message attached is `Back`.

or

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
    ];

    // Add back action
    $actions = $this->mergeAction($actions, $this->backAction());

    return $actions;
  }
}
```
The method will return the exact array that we harcoded in the first example.
You can modify the trigger by passing your custom trigger to the method as first parameter. You can custom the message attached by passing the sustom message as second parameter.

### Inserting Welcome action
You can add a go-to-welcome action to your actions by using the `__welcome` next menu or by calling the `$this->mainMenuAction()` method.

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
      
      // Add back action
      '00' => [
        'display' => 'Main menu',
        'next_menu' => '__welcome',
      ]
    ];

    return $actions;
  }
}
```
The trigger is `00` and the message attached is `Main menu`.

or

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
    ];

    // Add main menu action
    $actions = $this->mergeAction($actions, $this->mainMenuAction());

    return $actions;
  }
}
```
### Inserting Paginate forward actions
You can add a go-forward-in-pagination action by using the `__paginate_forward` next menu or by calling the `$this->paginateForwardAction()` method.

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
      
      // Add back action
      '00' => [
        'display' => 'More',
        'next_menu' => '__paginate_forward',
      ]
    ];

    return $actions;
  }
}
```
Since we are going back, we are using the same trigger and message attached as the normal go-back action. The trigger is `0` and the message attached is `Back`.

or

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
    ];

    // Add paginate back action
    $actions = $this->mergeAction($actions, $this->paginateBackAction());

    return $actions;
  }
}
```
### Inserting Paginate back actions
You can add a go-to-welcome action to your actions by using the `__paginate_back` next menu or by calling the `$this->paginateBackAction()` method.

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
      
      // Add back action
      '0' => [
        'display' => 'Back',
        'next_menu' => '__paginate_back',
      ]
    ];

    return $actions;
  }
}
```
Since we are going back, we are using the same trigger and message attached as the normal go-back action. The trigger is `0` and the message attached is `Back`.

or

```php
namespace App\Menus;

class ProcessBalanceRequest extends Menu
{
  public function actions($userPreviousResponses)
  {
    $actions = [
      // Define your actions here
    ];

    // Add paginate back action
    $actions = $this->mergeAction($actions, $this->paginateBackAction());

    return $actions;
  }
}
```

## Console commands
Rejoice ships with useful console features to allow you test quickly your application and other more.

### Running the simulator
You have access to two simulators, one in console, the other as a web interface.
You can provide the configuration of the simulator in the .env file:

#### Console simulator

Run the console simulator by running the command
```shell
php smile simulator:console
```
You can use the shortcut `php smile sim:con`
> Note: You can use shortcuts for any simulator command, provided it does not conflict with any other command.

<!-- <style>
  .note {
    padding: 5px 30px;
    margin: 20px auto;
    font-size:13px;
    font-family:verdana;
    border-left:3px solid;
    border-radius: 3px;
  }

  .note-info {
    background-color:rgba(1,1,1,0.1); color:rgba(0,0,0,1);
  }

  .note-primary {
    background-color:rgba(1,1,250,0.1); color:rgba(10,10,250,0.8);
  }

  .note-warning {
    background-color: rgba(255,150,0,0.2); color:rgba(255,150,0,1);
  }

  .note-danger {
    background-color:rgba(255,0,0,0.1); color:rgba(255,10,0,1);
  }
</style> -->