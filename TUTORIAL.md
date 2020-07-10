# REJOICE FRAMEWORK - TUTORIAL
- [REJOICE FRAMEWORK - TUTORIAL](#rejoice-framework---tutorial)
  - [Install the framework](#install-the-framework)
  - [Configuring the application](#configuring-the-application)
    - [The `.env` file](#the-env-file)
    - [The `config/` folder](#the-config-folder)
    - [Creating the ussd menu flows](#creating-the-ussd-menu-flows)
    - [Creating the menu entity](#creating-the-menu-entity)
      - [The `message` method](#the-message-method)
      - [The `actions` method](#the-actions-method)
      - [The `validate` method](#the-validate-method)
      - [The `saveAs` method](#the-saveas-method)
      - [The `before` hook](#the-before-hook)
      - [The `after` hook](#the-after-hook)
  - [Implements pagination](#implements-pagination)
    - [The `Paginator` trait](#the-paginator-trait)
    - [The paginator required methods](#the-paginator-required-methods)
      - [`PaginationFetch`](#paginationfetch)
      - [`PaginationTotal`](#paginationtotal)
      - [`paginationInsertActions`](#paginationinsertactions)


These are the steps we will need to perform to create our USSD applications.
- Configuring the application in the .env file and/or in the `config/` folder.
- Creating the ussd menu flows in the `app/Menus/DefaultMenus/menus.php` file (completely optional).
- Creating the menu entity for each menu screen that requires a extra business logic.
- Run the simulator to test the application.

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
| <small>Welcome to your first menu<br>Select an option<br>1. Get inspiring quote</small>|
| \|...                                                                      |
| 1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3<br>4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6<br>7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9<br>*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#                  |

In the action array, we map the character `1` to the *next_menu* `inspiring_quote`. It simply means, if the user responds `1` to the welcome menu, the inspiring_quote menu will be called and displayed. That is the main thing we need to know to be able to create all the menu that we want with any complex dynamism. **We match a user input to a menu to call.**

Let's run our application in a ussd simulator to show what it will look like in the real life.

### Running the simulator
Rejoice comes with a default console command called `smile` :)
To run the simulator, run this command in the console, at the root of our project:
```shell
~/www/rejoice-first-project$ php smile simulator run
```
You will get the following response:
```shell
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
<!-- 
```php
// app/Menus/DefaultMenus/menus.php

return [
  
  // The welcome menu
  'welcome' => [
    'message' => "Welcome to our first menu :)\nSelect an option",
    
    'actions' => [
      // Order the actions in the order in which you want them to show
      '1' => [ // If user inputs 1
        'display' => 'Get phone info',
        'next_menu' => 'phone_info' // Go to phone_info screen
      ],
      '2' => [ // If user selects 2
        'display' => 'Get inspiring quotes',
        'next_menu' => 'inspiring_quotes' // Go to inspiring_quotes plan screen
      ],
    ]
  ],

  'phone_info' => [
    'message' => 'Select an option',
    
    'actions' => [
      '1' => [ // If user selects 1
        'display' => 'Get your phone number',
        'next_menu' => 'phone_number' // Go to phone_number screen
      ],
      '0' => [ // If user selects 0
        'display' => 'Back',
        'next_menu' => '__back' // Go back
      ]
    ]
  ],

    'phone_number' => [
    'message' => 'Your phone number is :',
  ],

    'choose_bundle_screen' => [
    'message' => 'This is the midnight plan',
  ]
];
``` 

This will be displayed on the user's phone as something like:
| ******** 
 ------------------------------------------------------------------------|
| <small>Choose an option <br>1. Free plan<br>2. Midnight plan<br>0. Back</small>|
| \|...                                                                      |
| 1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3<br>4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6<br>7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9<br>*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;#                  |

Kindly open the file `app/Menus/DefaultMenus/menus.php` to see a typical example of the menu flow file.

At this point you will have a working application, but without any business logic associated.

To add business logic (connecting to the database, displaying from database, etc.) we will need a class call menu entity.
-->
### Creating the menu entity 


#### The `message` method

#### The `actions` method


#### The `validate` method

#### The `saveAs` method

#### The `before` hook

#### The `after` hook

## Implements pagination
### The `Paginator` trait
To implement pagination, you will probably need to use the paginator trait. It allows you to quickly deploy a paginable menu.

Note: The `Rejoice` framework handles two types of pagination.
The first one that we call `soft-pagination` is when all the data is retrieved and passed to the framework. It works fine when the data to paginate is small.
The second type of pagination called the `hard-pagination` is the one that requires the use of the first one is when a menu overflows
### The paginator required methods
#### `PaginationFetch`
Retrieve the data from the database. Must return the data retrieve.

#### `PaginationTotal`
Return the total of rows fetched from the database.

#### `paginationInsertActions`
When using the paginator trait, instead of the `actions` methods, you will use the `paginationInsertActions` method to specify how to insert the actions in your menu. This method will be used under the hood by the actions method to insert the actions automatically for you. 