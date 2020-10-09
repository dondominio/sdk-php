<?php

/**
 * The DonDominio API Client.
 * @package DonDominioPHP
 * @subpackage DonDominioAPIClient
 */

require_once('DonDominioAPI/DonDominioAPIClientPostCurl.php');

/**
 * Exceptions objects for the different error types returned by the API.
 */
require_once('DonDominioAPI/Exceptions.php');

/**
 * Response object.
 */
require_once('DonDominioAPI/DonDominioAPIResponse.php');

/**#@+
 * Module-specific wrappers.
 */
require_once('DonDominioAPI/Wrappers/Account.php');
require_once('DonDominioAPI/Wrappers/Contact.php');
require_once('DonDominioAPI/Wrappers/Domain.php');
require_once('DonDominioAPI/Wrappers/Tool.php');
require_once('DonDominioAPI/Wrappers/Service.php');
/**#@-*/

/**
 * The DonDominio API Client.
 */
class DonDominioAPI
{
	/**#@+
	 * Target API version of this library.
	 * Use $options['versionCheck'] to disable version checking.
	 * @var string
	 */
	public $api_version_major = '1';
	public $api_version_minor = '1';
	/**#@-*/

	protected $client;

	/**#@+
	 * Wrappers for each module in the API.
	 * @var object
	 */
	protected $account;
	protected $contact;
	protected $domain;
	protected $tool;
	protected $service;
	/**#@-*/

	/**
	 * Array of options for the ch.
	 * @var array
	 */
	protected $options = array(
		'endpoint' => 'https://simple-api.dondominio.net',
		'port' => 443,
		'apiuser' => '',
		'apipasswd' => '',
		'autoValidate' => true,
		'versionCheck' => true,
		'debug' => false,
		'timeout' => 15,
		'debugOutput' => null,
		'verifySSL' => false,
		'response' => array(
			'throwExceptions' => true
		),
		'userAgent' => array()
	);

	/**
	 * Initializing the client.
	 * @param array $options Array containing options for the client
	 * @throws DonDominioAPI_Error if no user or password is present
	 */
	public function __construct(array $options = null)
	{
		if( !extension_loaded('curl')){
			return null;
		}

		//Merging default & defined options
		if(is_array($options)){
			$this->options = array_merge($this->options, $options);
		}

		//Checking that we have an username & a password
		if(empty($this->options['apiuser']) || empty($this->options['apipasswd'])){
			throw new \DonDominioAPI_Error('You must provide an user and a password for the API');
		}

		//Initialize the cURL client
		$this->client = new DonDominioAPIClientPostCurl(array(
			'endpoint' => $this->options['endpoint'],
			'port' => $this->options['port'],
			'timeout' => $this->options['timeout'],
			'debug' => $this->options['debug'],
			'debugOutput' => $this->options['debugOutput'],
			'verifySSL' => $this->options['verifySSL'],
			'format' => 'json',
			'pretty' => false,
			'userAgent' => $this->options['userAgent']
		));

		//Modules
		$this->account = new DonDominioAPI_Account($this);
		$this->contact = new DonDominioAPI_Contact($this);
		$this->domain = new DonDominioAPI_Domain($this);
		$this->tool = new DonDominioAPI_Tool($this);
		$this->service = new DonDominioAPI_Service( $this );
	}

	public function close()
	{
		if( is_object( $this->client )){
			$this->client->close();
		}
	}

	/**
	 * Set an option.
	 *
	 * @param string $key Name of the option
	 * @param mixed $value New value for the option
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;
	}

	/**
	 * Get an option.
	 *
	 * @param string $key Name of the option
	 * @return mixed or null if not found
	 */
	public function getOption($key)
	{
		if(!array_key_exists($key, $this->options)){
			return null;
		}

		return $this->options[$key];
	}

	/**
	 * Automatically call a method inside a module wrapper from within the client.
	 * @param string $method Method called
	 * @param array $args Arguments passed to the method
	 * @return DonDominioResponse
	 */
	public function __call( $method, array $args = array())
	{
		if( strpos( $method, '_' ) === false ){
			trigger_error( 'Invalid call: ' . $method, E_USER_ERROR );
		}

		list( $class, $method ) = explode( '_', $method );

		if( !property_exists( $this, $class )){
			trigger_error( 'Undefined module: ' . $class, E_USER_ERROR );
		}

		return $this->$class->proxy( $method, $args );
	}

	/**
	 * Execute an API call through the client.
	 * @param string $url API endpoint
	 * @param array $args Arguments passed to the call
	 * @return array
	 */
	public function call( $url, array $args = array())
	{
		if( !extension_loaded('curl')){
			die( "cURL library no available. Use \"info\" for more information." );
		}

		$params = array_merge(
			array(
				'apiuser' => $this->options['apiuser'],
				'apipasswd' => $this->options['apipasswd']
			),
			$args
		);

		return $this->client->execute( $url, $params );
	}

	/**
	 * Check for requirements and valid settings.
	 */
	public function info()
	{
		/*
		 * Checking requirements.
		 */
		$phpMinVersion = "5.3.0";
		$phpVersionCheck = version_compare( PHP_VERSION, $phpMinVersion, ">=" );
		$osName = php_uname( 's' ) ?: PHP_OS;
		$osVersion = php_uname( 'v' );
		$curlCheck = extension_loaded('curl');
		$jsonCheck = extension_loaded('json');

		$ip = file_get_contents('https://api.ipify.org');

		print( PHP_EOL);

		printf( " Local IP address is %s" . PHP_EOL, $ip );

		print( PHP_EOL );

		print( " Requirements" . PHP_EOL );
		print( " ============" . PHP_EOL );

		printf( " PHP Version:\t\t\t%s\t%s" . PHP_EOL, 			( $phpVersionCheck ? "OK" : "X" ), PHP_VERSION );
		printf( " Operating system:\t\t\t%s" . PHP_EOL,			$osName );
		printf( " OS Version:\t\t\t\t%s" . PHP_EOL,				$osVersion );
		printf( " cURL Enabled:\t\t\t%s" . PHP_EOL,				( $curlCheck ? "OK" : "X" ));
		printf( " JSON Enabled:\t\t\t%s" . PHP_EOL,				( $jsonCheck ? "OK" : "X" ));

		print( PHP_EOL );

		/*
		 * Checking settings.
		 */
		$uri = $this->options['endpoint'];
		$uriCheck = !empty( $uri );
		$port = $this->options['port'];
		$portCheck = !empty( $port );
		$user = $this->options['apiuser'];
		$userCheck = !empty( $user );
		$pass = preg_replace( "/[^.]/i", "*", trim( $this->options['apipasswd'] ));
		$passCheck = !empty( $pass );

		print( " Settings" . PHP_EOL );
		print( " ========" . PHP_EOL );

		printf( " URI:\t\t\t\t\t%s\t%s" . PHP_EOL,					( $uriCheck ? "OK" : "X" ), $uri );
		printf( " Port:\t\t\t\t\t%s\t%s" . PHP_EOL,					( $portCheck ? "OK" : "X" ), $port );
		printf( " Username:\t\t\t\t%s\t%s" . PHP_EOL,				( $userCheck ? "OK" : "X" ), $user );
		printf( " Password:\t\t\t\t%s\t%s" . PHP_EOL,				( $passCheck ? "OK" : "X" ), $pass );
		printf( " Validate params:\t\t%s" . PHP_EOL,			( $this->options['autoValidate'] ? 'Yes' : 'No' ));
		printf( " Check new releases:\t%s" . PHP_EOL,			( $this->options['versionCheck'] ? 'Yes' : 'No' ));
		printf( " Debug mode:\t\t\t%s" . PHP_EOL,				( $this->options['debug'] ? 'Yes' : 'No' ));
		printf( " Debug output:\t\t\t%s" . PHP_EOL,				( $this->options['debugOutput'] ? 'Yes' : 'No' ));
		printf( " Request timeout:\t\t%d seconds" . PHP_EOL,	$this->options['timeout'] );
		printf( " Verify SSL certs:\t\t%s" . PHP_EOL,			( $this->options['verifySSL'] ? 'Yes' : 'No' ));
		printf( " Throw exceptions:\t\t%s" . PHP_EOL,			( $this->options['response']['throwExceptions'] ? 'Yes' : 'No' ));

		print( PHP_EOL );

		$error = false;

		if( !$phpVersionCheck ){
			$error = true;

			printf( " [!!] PHP Version %s or higher required. Your version is %s." . PHP_EOL, $phpMinVersion, PHP_VERSION );
		}

		if( !$curlCheck ){
			$error = true;

			print( " [!!] cURL library for PHP5 is required. More info: http://php.net/manual/en/book.curl.php" . PHP_EOL );
		}

		if( !$jsonCheck ){
			$error = true;

			print( " [!!] JSON library for PHP5 is required. More info: http://php.net/manual/en/book.json.php" . PHP_EOL );
		}

		if( !$uriCheck ){
			$error = true;

			print( "[!!] API URI cannot be blank. Check your API URI on https://www.dondominio.com/admin/account/api/" . PHP_EOL );
		}

		if( !$portCheck ){
			$error = true;

			print( " [!!] API Port cannot be blank. Check your API Port on https://www.dondominio.com/admin/account/api/" . PHP_EOL );
		}

		if( !$userCheck ){
			$error = true;

			print( " [!!] API Username cannot be blank. Check your API Username on https://www.dondominio.com/admin/account/api/" . PHP_EOL );
		}

		if( !$passCheck ){
			$error = true;

			print( " [!!] API Password cannot be blank. Set your API Password on https://www.dondominio.com/admin/account/api/" . PHP_EOL );
		}

		if( $error ){
			print( PHP_EOL );
			print( " Please, fix the indicated errors before using DonDominioAPI." . PHP_EOL );
			print( PHP_EOL );
			exit();
		}

		print( " Connection test" . PHP_EOL );
		print( " ===============" . PHP_EOL );

		print( " Executing `tool_hello`..." . PHP_EOL );
		print( PHP_EOL );

		try{
			$hello = $this->tool_hello();
		}catch( \DonDominioAPI_Error $e ){
			printf( " [!!] Connection failed with error %s" . PHP_EOL, $e->getMessage());
			print( PHP_EOL );
			exit();
		}

		print( " [OK] Success!" . PHP_EOL );

		print( PHP_EOL );

		printf( " Local IP:\t\t\t%s" . PHP_EOL,				$hello->get( 'ip' ));
		printf( " Language:\t\t\t%s" . PHP_EOL,				$hello->get( 'lang' ));
		printf( " API Version:\t\t\t%s" . PHP_EOL,			$hello->get( 'version' ));

		print( PHP_EOL );
	}
}