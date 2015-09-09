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
	public $api_version_major = '0';
	public $api_version_minor = '9';
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
		)
	);
	
	/**
	 * Initializing the client.
	 * @param array $options Array containing options for the client
	 * @throws DonDominioAPI_Error if no user or password is present
	 */
	public function __construct(array $options = null)
	{
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
			'pretty' => true
		));
		
		//Modules
		$this->account = new DonDominioAPI_Account($this);
		$this->contact = new DonDominioAPI_Contact($this);
		$this->domain = new DonDominioAPI_Domain($this);
		$this->tool = new DonDominioAPI_Tool($this);
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
	public function __call($method, array $args = array())
	{
		if(!strpos($method, '_')){
			trigger_error('Invalid call: ' . $method, E_USER_ERROR);
		}
		
		list($class, $method) = explode('_', $method);
		
		if(!property_exists($this, $class)){
			trigger_error('Undefined module: ' . $class, E_USER_ERROR);
		}
		
		return $this->$class->proxy($method, $args);
	}
	
	/**
	 * Execute an API call through the client.
	 * @param string $url API endpoint
	 * @param array $args Arguments passed to the call
	 * @return array
	 */
	public function call($url, array $args = array())
	{
		$params = array_merge(
			array(
				'apiuser' => $this->options['apiuser'],
				'apipasswd' => $this->options['apipasswd']
			),
			(is_array($args)) ? $args : array()
		);
		
		return $this->client->execute($url, $params);
	}
}

?>