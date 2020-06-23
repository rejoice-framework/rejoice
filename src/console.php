<?php
/**
 * @author Prince Dorcis <princedorcis@gmail.com>
 */

function generateJson()
{
    global $not_found_msg;
    $resource_dir = __DIR__ . '/../../../../app/resources';

    $menus = processCommand($resource_dir . '/menus.php', $not_found_msg);

    $json_path = $resource_dir . '/menus.json';

    $write = true;
    if (file_exists($json_path)) {
        $ask = colorConsole("Are you sure to override existing menus.json? [no]: ", ['fg' => 'red']);
        $res = readConsoleLine($ask);
        $write = in_array(strtolower($res), ['y', 'yes']);
    }

    if ($write) {
        $created = file_put_contents($json_path, json_encode($menus, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));

        if ($created !== false) {
            echo colorConsole("\njson generated successfully in app/resources/menus.json", ['fg' => 'green']);
        } else {
            echo colorConsole("\nError when generating the json file.", ['fg' => 'red']);
        }
    } else {
        echo colorConsole('menus.json creation discarded.', ['fg' => 'yellow']);
    }

}

if ($_SERVER['argc'] > 1 && $_SERVER['argv'][1] === 'generate:json') {
    generateJson();
} else {
    echo colorConsole('Command not found.', ['fg' => 'red']) . "\nNo stress.\nKeep smiling 😃\n\n";
}
