<?php

/**
 * Info & requirement check from DonDominioAPI.
 * Use `info` to check if everything is correct to use the API & test connection.
 */

header("Content-Type: text/plain; charset=utf-8");

// First, put here your API User & Password
define('YOUR_API_USER', '');
define('YOUR_API_PASSWORD', '');

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'src', 'autoloader.php']);

try {
    $dondominio = new \Dondominio\API\API([
        'apiuser' => YOUR_API_USER,
        'apipasswd' => YOUR_API_PASSWORD
    ]);

    $info = $dondominio->info();
} catch (\Dondominio\API\Exceptions\Error $e) {
    print(PHP_EOL);
    print(" Error initializing SDK: " . $e->getMessage());
    print(PHP_EOL);
    print(PHP_EOL);
    print(" Edit config-check.php and check your settings, then try again.");
}
