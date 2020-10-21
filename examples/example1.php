<?php

/**
 * Example 1. Getting the availability of a domain.
 */

//First, put here your API User & Password
define('YOUR_API_USER', '');
define('YOUR_API_PASSWORD', '');

require_once implode(DIRECTORY_SEPARATOR, [dirname(dirname(__FILE__)), 'vendor', 'autoload.php']);

$dondominio = new \Dondominio\API\API([
    'apiuser' => YOUR_API_USER,
    'apipasswd' => YOUR_API_PASSWORD
]);

$domainCheck = $dondominio->domain_check('default.com');

$domains = [];

$domains = $domainCheck->get('domains');

if (count($domains) > 0) {
    foreach ($domains as $key => $domain) {
        $name = $domain['name'];
        $available = $domain['available'] ? 'Yes' : 'No';
        $premium = $domain['premium'] ? 'Yes' : 'No';
        $tld = $domain['tld'];
        $price = $domain['price'] . ' ' . $domain['currency'];

        echo $name . PHP_EOL;
        echo "   Available:\t" . $available . PHP_EOL;
        echo "   Premium:  \t" . $premium . PHP_EOL;
        echo "   TLD:      \t" . $tld . PHP_EOL;
        echo "   Price:    \t" . $price . PHP_EOL;
        echo PHP_EOL;
    }
} else {
    echo "No information found." . PHP_EOL;
}