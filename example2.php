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
	'apipasswd' => YOUR_API_PASSWORD,
	'response' => array(
		'throwExceptions' => true
	)
));

$domainCheck = $dondominio->domain_check('pepe12345.com');

$domains = array();

try{
	$domains = $domainCheck->get('domains');
}catch(DonDominioAPI_Error $e){
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

		echo "Register successful\r\n";
		echo "   Amount:    \t" . $billing['total'] . " " . $billing['currency'] . "\r\n";
		echo "\r\n";

		foreach($domains as $key=>$domain){
			echo $domain['name'] . "\r\n";
			echo "   Status:    \t" . $domain['status'] . "\r\n";
			echo "   TLD:       \t" . $domain['tld'] . "\r\n";
			echo "   Expiration:\t" . $domain['tsExpir'] . "\r\n";
			echo "   Domain ID: \t" . $domain['domainID'] . "\r\n";
			echo "   Period:    \t" . $domain['period'] . "\r\n";
		}
	}catch(DonDominioAPI_Error $e){
		die('Error found: ' . $e->getMessage());
	}
}else{
	echo "Register failed.\r\n";
}