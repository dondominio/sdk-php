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
		
		echo $name . "\r\n";
		echo "   Available:\t" . $available . "\r\n";
		echo "   Premium:  \t" . $premium . "\r\n";
		echo "   TLD:      \t" . $tld . "\r\n";
		echo "   Price:    \t" . $price . "\r\n";
		echo "\r\n";
	}
}else{
	echo "No information found.\r\n";
}

?>