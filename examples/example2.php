<?php

/**
 * Example 1. Getting the availability of a domain.
 */

//First, put here your API User & Password
define('YOUR_API_USER', '');
define('YOUR_API_PASSWORD', '');

require_once implode(DIRECTORY_SEPARATOR, [dirname(dirname(__FILE__)), 'vendor', 'autoload.php']);

$dondominio = new \Dondominio\API\API(array(
	'apiuser' => YOUR_API_USER,
	'apipasswd' => YOUR_API_PASSWORD,
	'response' => array(
		'throwExceptions' => true
	)
));

$domainCheck = $dondominio->domain_check('pepe12345.com');

$domains = array();

try{
	$domains = $domainCheck->get('domains');
}catch(\Dondominio\API\Exceptions\Error $e){
	die('Error found: ' . $e->getMessage());
}

if(count($domains > 0) && $domains[0]['available'] == true){
	//The domain is available to register
	$data = array(
		'period' => 1,
		'premium' => false,
		'nameservers' => 'ns1.dns.com,ns2.dns.com',
		'ownerContactType' => 'individual',
		'ownerContactFirstName' => 'John',
		'ownerContactLastName' => 'Doe',
		'ownerContactIdentNumber' => 'XX00000',
		'ownerContactPhone' => '+00.00000000',
		'ownerContactEmail' => 'john.doe@example.com',
		'ownerContactAddress' => 'Example Address 123',
		'ownerContactCity' => 'Example City',
		'ownerContactPostalCode' => '00000',
		'ownerContactState' => 'Example State',
		'ownerContactCountry' => 'US'
	);

	try{
		$domainCreate = $dondominio->domain_create(
			'pepe12345.com',
			$data
		);

		$billing = $domainCreate->get('billing');
		$domains = $domainCreate->get('domains');

		echo "Register successful" . PHP_EOL;
		echo "   Amount:    \t" . $billing['total'] . " " . $billing['currency'] . PHP_EOL;
		echo PHP_EOL;

		foreach($domains as $key=>$domain){
			echo $domain['name'] . PHP_EOL;
			echo "   Status:    \t" . $domain['status'] . PHP_EOL;
			echo "   TLD:       \t" . $domain['tld'] . PHP_EOL;
			echo "   Expiration:\t" . $domain['tsExpir'] . PHP_EOL;
			echo "   Domain ID: \t" . $domain['domainID'] . PHP_EOL;
			echo "   Period:    \t" . $domain['period'] . PHP_EOL;
		}
	}catch(\Dondominio\API\Exceptions\Error $e){
		die('Error found: ' . $e->getMessage());
	}
}else{
	echo "Register failed." . PHP_EOL;
}