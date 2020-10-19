<?php

/**
 * Example 4. Retrieving information from a service.
 */

//First, put here your API User & Password
define( 'YOUR_API_USER', '' );
define( 'YOUR_API_PASSWORD', '' );

require_once implode(DIRECTORY_SEPARATOR, [dirname(dirname(__FILE__)), 'vendor', 'autoload.php']);

$dondominio = new \Dondominio\API\API(array(
	'apiuser' => YOUR_API_USER,
	'apipasswd' => YOUR_API_PASSWORD
));

$services = $dondominio->service_getInfo( 'default.com', array( 'infoType' => 'status' ));

print_r( $services );