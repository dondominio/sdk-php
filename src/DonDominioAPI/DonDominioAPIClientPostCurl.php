<?php

/**
 * POST cURL client for the DonDominio API (using SIMPLE-POST).
 * @package DonDominioPHP
 * @subpackage Clients
 */

/**
 * Interface for DonDominio API clients.
 */
require_once( 'DonDominioAPIClientInterface.php' );

/**
 * POST cURL client for the DonDominio API (using SIMPLE-POST).
 */
class DonDominioAPIClientPostCurl implements DonDominioAPIClientInterface
{
	/**
	 * cURL instance used by the client.
	 * @var resource
	 */
	protected $ch;
	
	protected $userAgent = array(
		'ClientPlatform' => 'PHP',
		'ClientVersion' => '1.5',
		'PHPVersion' => '',
		'OperatingSystem' => '',
		'OperatingSystemVersion' => ''
	);
	
	/**
	 * Array of options for the client.
	 * @var array
	 */
	protected $options = array(
		'endpoint' => 'https://simple-api.dondominio.net',
		'port' => 443,
		'timeout' => 15,
		'debug' => false,
		'debugOutput' => null,
		'verifySSL' => false,
		'format' => 'json',
		'pretty' => false
	);
	
	/**
	 * Merge options passed and initialize the client.
	 */
	public function __construct( array $options = array())
	{
		//Merging default & defined options
		if( is_array( $options )){
			$this->options = array_merge( $this->options, $options );
		}
		
		$operatingSystem = php_uname( 's' );
		$operatingSystemVersion = php_uname( 'v' );
		
		if( empty( $operatingSystem )){
			$operatingSystem = PHP_OS;
		}
		
		$this->userAgent['OperatingSystem'] = $operatingSystem;
		$this->userAgent['OperatingSystemVersion'] = $operatingSystemVersion;
		$this->userAgent['PHPVersion'] = phpversion();
		
		$this->userAgent = array_merge( $this->userAgent, $options['userAgent'] );
		
		$this->init();
	}
	
	/**
	 * Call an API endpoint.
	 * @param string $url URL to be requested
	 * @param array $args Parameters to be submitted along the request
	 * @throws DonDominioAPI_HttpError on connection error
	 * @throws DonDominioAPI_Error on 
	 * @return array|string
	 */
	public function execute( $url, array $args = array())
	{
		$ch = $this->ch;
		
		$params = array_merge(
			array(
				'output-format' => $this->options['format'],
				'output-pretty' => $this->options['pretty']
			),
			( is_array( $args )) ? $args : array()
		);
				
		curl_setopt( $ch, CURLOPT_URL, trim( $this->options['endpoint'] ) . '/' . trim( $url ));
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
		
		$start = microtime( true );
		
		$this->log( 'Calling: ' . $this->options['endpoint'] . '/' . $url );
		$this->log( 'Parameters: ' . json_encode( $params ));
		
		if( $this->options['debug'] ){
			//Saving cURL output to memory for later
			$curl_buffer = fopen( 'php://memory', 'w+' );
			curl_setopt( $ch, CURLOPT_STDERR, $curl_buffer );
		}
		
		$response = curl_exec( $ch );
		
		$info = curl_getinfo( $ch );
		
		$time = microtime( true ) - $start;
		
		if( $this->options['debug'] ){
			//Reading cURL buffer contents
			rewind( $curl_buffer );
			$this->log( stream_get_contents( $curl_buffer ));
			fclose( $curl_buffer );
		}
		
		$this->log( 'Completed in ' . number_format( $time * 1000, 2 ) . 'ms' );
		$this->log( 'Response: ' . $response );
		
		//Checking for errors in cURL
		if( curl_error( $ch )){
			return null;
		}
		
		return $response;
	}
	
	/**
	 * Add an user agent to the array.
	 *
	 * @param string $value Name of the User Agent
	 * @param string $version Version
	 * @return boolean
	 */
	public function addUserAgent( $value, $version )
	{
		$this->userAgent[ $value ] = $version;
		
		return true;
	}
	
	/**
	 * Build the user agent string from the array.
	 *
	 * @return string
	 */
	protected function buildUserAgent()
	{
		$userAgentString = '';
		
		foreach( $this->userAgent as $key=>$value ){
			$userAgentString .= $key . '/' . $value . ';';
		}
		
		return $userAgentString;
	}
	
	/**
	 * Initialize the cURL client.
	 */
	protected function init()
	{
		$this->ch = curl_init();
		
		curl_setopt( $this->ch, CURLOPT_USERAGENT, $this->buildUserAgent());
		curl_setopt( $this->ch, CURLOPT_POST, true );
		curl_setopt( $this->ch, CURLOPT_HEADER, false );
		curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, true );
		 
		if( $this->options['verifySSL'] == true ){
			curl_setopt( $this->ch, CURLOPT_SSL_VERIFYPEER, 1 );
			curl_setopt( $this->ch, CURLOPT_SSL_VERIFYHOST, 2 );
		}
		
		curl_setopt( $this->ch, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt( $this->ch, CURLOPT_PORT, $this->options['port'] );
		curl_setopt( $this->ch, CURLOPT_TIMEOUT, $this->options['timeout'] );
		curl_setopt( $this->ch, CURLOPT_VERBOSE, $this->options['debug'] );
	}
	
	/**
	 * Log a message to the selected logging system, if logging is enabled.
	 * The logging system can be controlled using the "debugOutput" option.
	 *
	 * Use $output = 'error_log' to output to the default PHP error_log.
	 * Use $output = 'php://stdout' to output to the default stdout.
	 * Use $output with a filename to write the log to that file.
	 *
	 * @param string $message Message to be logged
	 */
	protected function log( $message )
	{
		if( $this->options['debug'] == true ){
			$output = ( empty($this->options['debugOutput'] )) ? 'php://stdout' : $this->options['debugOutput'];
			
			if( $output == 'error_log' ){
				//Log to default logging system
				error_log( $message );
			}else{
				//Otherwise, log to file
				file_put_contents( $output, '[' . date('m/d/Y H:i:s') . '] ' . $message."\r\n", FILE_APPEND );
			}
		}
	}
	
	/**
	 * Freeing resources.
	 */
	public function __destruct()
	{
		if( $this->ch ){
			curl_close( $this->ch );
		}
	}
}