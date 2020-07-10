<?php
use function Prinx\Dotenv\env;

// Need work
/**
 * The folder design of the framework is essentially made to be the most
 * compatible to the Laravel framework. However, you can modify this file to
 * integrate in another framework that does not use the same folder design
 */
return [
    'menus_root_path' => realpath(__DIR__ . '/../../../../../resources/menus/'),
    'config_root_path' => realpath(__DIR__ . '/../../../../../config/'),
    'storage_root_path' => realpath(__DIR__ . '/../../../../../storage/logs/'),
    'logs_root_path' => realpath(__DIR__ . '/../../../../../storage/logs/'),
    'sessions_root_path' => realpath(__DIR__ . '/../../../../../storage/sessions/'),
    'app_config_path' => realpath(__DIR__ . '/../../../../../config/app.php'),
    'database_config_path' => realpath(__DIR__ . '/../../../../../config/database.php'),
    'session_config_path' => realpath(__DIR__ . '/../../../../../config/session.php'),
    'default_env' => realpath(__DIR__ . '/../../../../../.env'),
    'default_namespace' => 'Prinx\Rejoice\\',
];
