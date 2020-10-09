<?php

/**
 * Example 1. Getting the availability of a domain.
 */

//First, put here your API User & Password
define('YOUR_API_USER', '');
define('YOUR_API_PASSWORD', '');

require_once('src/DonDominioAPI.php');

$dondominio = new DonDominioAPI(array(
	'apiuser' => YOUR_API_USER,
	'apipasswd' => YOUR_API_PASSWORD
));

$domainCheck = $dondominio->domain_check('default.com');

$domains = array();

$domains = $domainCheck->get('domains');

if(count($domains) > 0){
	foreach($domains as $key=>$domain){
		$name = $domain['name'];
		$available = ($domain['available'] == true) ? 'Yes' : 'No';
		$premium = ($domain['premium'] == true) ? 'Yes' : 'No';
		$tld = $domain['tld'];
		$price = $domain['price'] . ' ' . $domain['currency'];

		echo $name . PHP_EOL;
		echo "   Available:\t" . $available . PHP_EOL;
		echo "   Premium:  \t" . $premium . PHP_EOL;
		echo "   TLD:      \t" . $tld . PHP_EOL;
		echo "   Price:    \t" . $price . PHP_EOL;
		echo PHP_EOL;
	}
}else{
	echo "No information found." . PHP_EOL;
}