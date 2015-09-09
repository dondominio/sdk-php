<?php

/**
 * DonDominio API response object.
 * @package DonDominioPHP
 * @subpackage Response
 */

/**
 * DonDominio API response object.
 */
class DonDominioAPIResponse
{
	/**
	 * Response in array format.
	 * @var array
	 */
	protected $response;
	
	/**
	 * Response in RAW format (JSON string).
	 * @var string
	 */
	protected $rawResponse;
	
	/**
	 * Options.
	 * @var array
	 */
	protected $options = array(
		'throwExceptions' => false
	);
	
	/**
	 * Exception mapping.
	 * @var array
	 */
	protected static $errorMap = array(
		'-1' => 'DonDominioAPI_ValidationError',
		
		'1' => 'DonDominioAPI_UndefinedError',
		
		'100' => 'DonDominioAPI_SyntaxError',
		'101' => 'DonDominioAPI_SyntaxError_ParameterFault',
		'102' => 'DonDominioAPI_ObjectOrAction_NotValid',
		'103' => 'DonDominioAPI_ObjectOrAction_NotAllowed',
		'104' => 'DonDominioAPI_ObjectOrAction_NotImplemented',
		'105' => 'DonDominioAPI_SyntaxError_InvalidParameter',
		
		'200' => 'DonDominioAPI_Login_Required',
		'201' => 'DonDominioAPI_Login_Invalid',
		'210' => 'DonDominioAPI_Session_Invalid',
		
		'300' => 'DonDominioAPI_Action_NotAllowed',
		
		'1000' => 'DonDominioAPI_Account_Blocked',
		'1001' => 'DonDominioAPI_Account_Deleted',
		'1002' => 'DonDominioAPI_Account_Inactive',
		'1003' => 'DonDominioAPI_Account_NotExists',
		'1004' => 'DonDominioAPI_Account_InvalidPass',
		'1005' => 'DonDominioAPI_Account_InvalidPass',
		'1006' => 'DonDominioAPI_Account_Blocked',
		'1007' => 'DonDominioAPI_Account_Filtered',
		'1009' => 'DonDominioAPI_Account_InvalidPass',
		'1010' => 'DonDominioAPI_Account_Blocked',
		'1011' => 'DonDominioAPI_Account_Blocked',
		'1012' => 'DonDominioAPI_Account_Blocked',
		'1013' => 'DonDominioAPI_Account_Blocked',
		'1014' => 'DonDominioAPI_Account_Filtered',
		'1015' => 'DonDominioAPI_Account_Banned',
		
		'1100' => 'DonDominioAPI_InsufficientBalance',
		
		'2001' => 'DonDominioAPI_InvalidDomainName',
		'2002' => 'DonDominioAPI_TLD_NotSupported',
		'2003' => 'DonDominioAPI_TLD_UnderMaintenance',
		'2004' => 'DonDominioAPI_Domain_CheckError',
		'2005' => 'DonDominioAPI_Domain_TransferNotAllowed',
		'2006' => 'DonDominioAPI_Domain_WhoisNotAllowed',
		'2007' => 'DonDominioAPI_Domain_WhoisError',
		'2008' => 'DonDominioAPI_Domain_NotFound',
		'2009' => 'DonDominioAPI_Domain_CreateError',
		'2010' => 'DonDominioAPI_Domain_CreateError_Taken',
		'2011' => 'DonDominioAPI_Domain_CreateError_PremiumDomain',
		'2012' => 'DonDominioAPI_Domain_TransferError',
		
		'2100' => 'DonDominioAPI_Domain_RenewError',
		'2101' => 'DonDominioAPI_Domain_RenewNotAllowed',
		'2102' => 'DonDominioAPI_Domain_RenewBlocked',
		
		'2200' => 'DonDominioAPI_Domain_UpdateError',
		'2201' => 'DonDominioAPI_Domain_UpdateNotAllowed',
		'2202' => 'DonDominioAPI_Domain_UpdateBlocked',
		
		'3001' => 'DonDominioAPI_Contact_NotExists',
		'3002' => 'DonDominioAPI_Contact_DataError'
	);
	
	/**
	 * Take a response string in JSON format and an array of options to initialize the class.
	 * @param string $response Response in JSON format
	 * @param array $options Options passed to the class
	 */
	public function __construct($response = null, array $options = null)
	{
		$this->rawResponse = $response;
		
		$this->response = @json_decode($this->rawResponse, true);
		
		$this->options = array_merge(
			$this->options,
			(is_array($options)) ? $options : array()
		);
		
		$this->readResponse();
	}
	
	/**
	 * Check for errors in the response, and throw appropriate exceptions.
	 * @throws DonDominioAPI_Error on invalid responses and errors
	 */
	protected function readResponse()
	{
		if($this->options['throwExceptions']){
			if(!is_array($this->response) || !array_key_exists('success', $this->response)){
				throw new DonDominioAPI_Error('Invalid response');
			}
			
			if($this->response['success'] != true){
				throw $this->castError($this->response);
			}
		}
	}
	
	/**
	 * Get the "success" parameter from the response.
	 * @return boolean
	 */
	public function success()
	{
		return $this->response['success'];
	}
	
	/**
	 * Get the "errorCode" parameter from the response.
	 * @return string
	 */
	public function getErrorCode()
	{
		return $this->response['errorCode'];
	}
	
	/**
	 * Get the "errorCodeMsg" parameter from the response.
	 * @return string
	 */
	public function getErrorCodeMsg()
	{
		return $this->response['errorCodeMsg'];
	}
	
	/**
	 * Get the "action" parameter from the response.
	 * @return string
	 */
	public function getAction()
	{
		return $this->response['action'];
	}
	
	/**
	 * Get the "version" parameter from the response.
	 * @return string
	 */
	public function getVersion()
	{
		return $this->response['version'];
	}
	
	/**
	 * Get all of the values in the "responseData" field in array format.
	 * @return array
	 */
	public function getResponseData()
	{
		return $this->response['responseData'];
	}
	
	/**
	 * Get a parameter from the "responseData" array.
	 * @param string $key Key of the parameter
	 * @return mixed or false if $key not found
	 */
	public function get($key)
	{
		if(array_key_exists($key, $this->response['responseData'])){
			return $this->response['responseData'][$key];
		}else{
			return false;
		}
	}
	
	/**
	 * Get the response in raw JSON string format.
	 * @return string
	 */
	public function getRawResponse()
	{
		return $this->rawResponse;
	}
	
	/**
	 * Get the response in array format.
	 * @return array
	 */
	public function getArray()
	{
		return $this->response;
	}
	
	/**
	 * Output the response in the specified format.
	 * @param string $method Output filter
	 * @param array $args Optional arguments for the call
	 */
	public function output($format, array $args = array())
	{
		$filter = 'OutputFilter' . $format;
				
		$path = __DIR__ . '/outputFilters/' . $filter . '.php';
		
		if(!file_exists($path)){
			trigger_error('Output filter not found: ' . $path, E_USER_ERROR);
		}
		
		require_once($path);
		
		if(!class_exists($filter)){
			trigger_error('Not a valid Output Filter: ' . $filter, E_USER_ERROR);
		}
		
		$outputFilter = new $filter($args);
		
		if(!method_exists($outputFilter, 'render')){
				trigger_error('Method render not found in output filter ' . $filter, E_USER_ERROR);
		}
		
		return $outputFilter->render($this->response['responseData']);
	}
	
	/**
	 * Throw the appropriate exception for the error received.
	 * @param array $result Response from the API; an error response is expected
	 * @throws DonDominioAPI_Error or the appropriate exception
	 */
	public function castError(array $result)
	{
		if(!$result['errorCode']){
			throw new \DonDominioAPI_Error('Got an unexpected error: ' . @json_encode($result));
		}
		
		$class = (isset(self::$errorMap[$result['errorCode']])) ? self::$errorMap[$result['errorCode']] : 'DonDominioAPI_Error';
		return new $class($result['messages'], $result['errorCode']);
	}
}

?>