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
class DonDominioAPI extends DonDominioAPIClientPostCurl
{
	/**#@+
	 * Target API version of this library.
	 * Use $options['versionCheck'] to disable version checking.
	 * @var string
	 */
	public $api_version_major = '1';
	public $api_version_minor = '1';
	/**#@-*/
	
	/**#@+
	 * Wrappers for each module in the API.
	 * @var object
	 */
	protected $account;
	protected $contact;
	protected $domain;
	protected $tool;
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
		if( in_array( 'curl', get_loaded_extensions())){
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
		if( !strpos( $method, '_' )){
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
		if( !in_array( 'curl', get_loaded_extensions())){
			die( "cURL library no available. Use \"info\" for more information." );
		}
		
		$params = array_merge(
			array(
				'apiuser' => $this->options['apiuser'],
				'apipasswd' => $this->options['apipasswd']
			),
			( is_array( $args )) ? $args : array()
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
		$phpVersion = phpversion();
		$phpVersionCheck = version_compare( phpversion(), "5.2.0" ) >= 0;
		$osName = php_uname( 's' );
		$osVersion = php_uname( 'v' ); if( empty( $osVersion )) $osVersion = PHP_OS;
		$curlCheck = in_array( 'curl', get_loaded_extensions());
		$jsonCheck = in_array( 'json', get_loaded_extensions());
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_URL, 'https://api.ipify.org' );
		$ip = curl_exec( $ch );
		
		print( "\r\n" );
		
		printf( " Local IP address is %s\r\n", $ip );
		
		print( "\r\n" );
		
		print( " Requirements\r\n" );
		print( " ============\r\n" );
		
		printf( " PHP Version:\t\t%s\t%s\r\n", 			( $phpVersionCheck ) ? "OK" : "X", $phpVersion );
		printf( " Operating system:\t\t%s\r\n",			$osName );
		printf( " OS Version:\t\t\t%s\r\n",				$osVersion );
		printf( " cURL Enabled:\t\t%s\r\n",				( $curlCheck ) ? "OK" : "X" );
		printf( " JSON Enabled:\t\t%s\r\n",				( $jsonCheck ) ? "OK" : "X" );
		
		print( "\r\n" );
		
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
		
		print( " Settings\r\n" );
		print( " ========\r\n" );
		
		printf( " URI:\t\t\t%s\t%s\r\n",				( $uriCheck ) ? "OK" : "X", $uri );
		printf( " Port:\t\t\t%s\t%s\r\n",				( $portCheck ) ? "OK" : "X", $port );
		printf( " Username:\t\t%s\t%s\r\n",				( $userCheck ) ? "OK" : "X", $user );
		printf( " Password:\t\t%s\t%s\r\n",				( $passCheck ) ? "OK" : "X", $pass );
		printf( " Validate params:\t\t%s\r\n",			( $this->options['autoValidate'] ) ? 'Yes' : 'No' );
		printf( " Check new releases:\t\t%s\r\n",		( $this->options['versionCheck'] ) ? 'Yes' : 'No' );
		printf( " Debug mode:\t\t\t%s\r\n",				( $this->options['debug'] ) ? 'Yes' : 'No' );
		printf( " Debug output:\t\t\t%s\r\n",			( $this->options['debugOutput'] ) ? 'Yes' : 'No' );
		printf( " Request timeout:\t\t%d seconds\r\n",	$this->options['timeout'] );
		printf( " Verify SSL certs:\t\t%s\r\n",			( $this->options['verifySSL'] ) ? 'Yes' : 'No' );
		printf( " Throw exceptions:\t\t%s\r\n",			( $this->options['response']['throwExceptions'] ) ? 'Yes' : 'No' );
		
		print( "\r\n" );
		
		$error = false;
		
		if( !$phpVersionCheck ){
			$error = true;
			
			printf( " [!!] PHP Version 5.2.0 or higher required. Your version is %s.\r\n", $phpVersion );
		}
		
		if( !$curlCheck ){
			$error = true;
			
			printf( " [!!] cURL library for PHP5 is required. More info: http://php.net/manual/en/book.curl.php\r\n", $phpVersion );
		}
		
		if( !$jsonCheck ){
			$error = true;
			
			printf( " [!!] JSON library for PHP5 is required. More info: http://php.net/manual/en/book.json.php\r\n", $phpVersion );
		}
		
		if( !$uriCheck ){
			$error = true;
			
			printf( "[!!] API URI cannot be blank. Check your API URI on https://www.dondominio.com/admin/account/api/\r\n", $phpVersion );
		}
		
		if( !$portCheck ){
			$error = true;
			
			printf( " [!!] API Port cannot be blank. Check your API Port on https://www.dondominio.com/admin/account/api/\r\n", $phpVersion );
		}
		
		if( !$userCheck ){
			$error = true;
			
			printf( " [!!] API Username cannot be blank. Check your API Username on https://www.dondominio.com/admin/account/api/\r\n", $phpVersion );
		}
		
		if( !$passCheck ){
			$error = true;
			
			printf( " [!!] API Password cannot be blank. Set your API Password on https://www.dondominio.com/admin/account/api/\r\n", $phpVersion );
		}
		
		if( $error ){
			print( "\r\n" );
			print( " Please, fix the indicated errors before using DonDominioAPI.\r\n" );
			print( "\r\n" );
			exit();
		}
		
		print( " Connection test\r\n" );
		print( " ===============\r\n" );
		
		print( " Executing `tool_hello`...\r\n" );
		print( "\r\n" );
		
		try{
			$hello = $this->tool_hello();
		}catch( \DonDominioAPI_Error $e ){
			printf( " [!!] Connection failed with error %s\r\n", $e->getMessage());
			print( "\r\n" );
			exit();
		}
		
		print( " [OK] Success!\r\n" );
		
		print( "\r\n" );
		
		printf( " Local IP:\t\t\t%s\r\n",				$hello->get( 'ip' ));
		printf( " Language:\t\t\t%s\r\n",				$hello->get( 'lang' ));
		printf( " API Version:\t\t\t%s\r\n",			$hello->get( 'version' ));
		
		print( "\r\n" );
	}
}

?>