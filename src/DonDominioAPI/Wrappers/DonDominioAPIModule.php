<?php

/**
 * Base for DonDominio API module wrappers.
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

/**
 * Base for DonDominio API module wrappers.
 */
abstract class DonDominioAPIModule
{
	/**
	 * Get the DonDominio client instance to use only one http client.
	 * @param DonDominio $master DonDominio client instance
	 */
	public function __construct(DonDominioAPI $master)
	{
		$this->master = $master;
	}
	
	/**
	 * Proxy a request to a method in this class and return its result.
	 * @param string $method Requested method
	 * @param array $args Arguments passed to the method
	 * @return DonDominioResponse
	 */
	public function proxy($method, array $args = array())
	{
		$class_name = __CLASS__;
		
		if(strpos('_', __CLASS__)){
			$class_name = explode('_', __CLASS__)[1];
		}
		
		if(!method_exists($this, $method)){
			trigger_error('Method ' . $method . ' not found in ' . $class_name, E_USER_ERROR);
		}
		
		return call_user_func_array(array($this, $method), $args);
	}
	
	/**
	 * Output the response in the appropriate format.
	 * @param string $response Response in JSON format
	 * @return string|array|DonDominioResponse
	 */
	protected function output($response)
	{
		return new DonDominioAPIResponse(
			$response,
			$this->master->getOption('response')
		);
	}
	
	/**
	 * Simulates an error from the API for internal purposes.
	 * This makes an error produced by the client compatible with the
	 * DonDominioResponse object.
	 * @param string $message Error message
	 * @param integer $errorCode Optional error code
	 * @return string
	 */
	protected function error($errorCodeMsg, $errorCode = -1, array $messages = array())
	{
		return json_encode(
			array(
				'success'=>false,
				'errorCode'=>$errorCode,
				'errorCodeMsg'=>$errorCodeMsg,
				'action'=>'',
				'version'=>'',
				'messages'=>$messages
			)
		);
	}
	
	/**
	 * Validate the parameters passed to a call and return the result from the API call.
	 * @param string $url URL to be called
	 * @param array $params Parameters to be passed to the call
	 * @param array $map Map of validations to perform against the parameters
	 * @return DonDominioResponse
	 */
	protected function execute($url, array $params = array(), array $map = array())
	{	
		$errors = array();
		
		if(count($map) > 0 && $this->master->getOption('autoValidate') == true){
			$errors = $this->validate($params, $map);
		}
		
		if(is_array($errors) && count($errors) > 0){
			return $this->output(
				$this->error(
					'Validation error',
					-1,
					$errors
				)
			);
		}else{
			$output = $this->output($this->master->call($url, $params));
			
			//Check the version from the API call to see if it matches the version
			//of this client.
			if($this->master->getOption('versionCheck')){
				$version = explode('.', $output->getVersion());
				
				if($this->master->api_version_minor < $version[1] && $this->master->api_version_major == $version[0]){
					trigger_error('This client is not up to date. It\'s recommended to update it to match the API version being used.', E_USER_WARNING);
				}
				
				if($this->master->api_version_major < $version[0]){
					trigger_error('This client is deprecated. You must update to the latest version.', E_USER_ERROR);
				}
			}
			
			return $output;
		}
	}
	
	/**
	 * Validate parameters passed to a call and return an array of the errors found.
	 * @param array $params Parameters passed to the call
	 * @param array $map Map of validations to perform against the parameters
	 * @return array
	 */
	protected function validate(array $params = array(), array $map = array())
	{
		$errors = array();
		
		foreach($map as $key=>$parameter){
			if($parameter['required'] == true && (!array_key_exists($parameter['name'], $params) || empty($params[$parameter['name']]))){
				if(array_key_exists('bypass', $parameter)){
					$bypass = $parameter['bypass'];
					
					if(!array_key_exists($bypass, $params) || empty($params[$bypass])){
						$errors[] = 'Parameter "' . $parameter['name'] . '" missing';
					}
				}else{				
					$errors[] = 'Parameter "' . $parameter['name'] . '" missing';
				}
			}
			
			if(array_key_exists($parameter['name'], $params)){
				$value = $params[$parameter['name']];
				
				if(!is_null($value)){
					switch($parameter['type']){
					/*
					 * LIST
					 * When validating against a list, the provided value must match one of
					 * the possible values listed in the validator.
					 */
					case 'list':
						if(!in_array($value, $parameter['list'])){
							$errors[] = '"' . $value . '" is not a valid value for parameter "' . $parameter['name'] . '". Accepted values: "' . implode('", "', $parameter['list']) . '"';
						}
						
						break;
					
					/*
					 * BOOLEAN
					 */
					case 'boolean':
						if(!is_bool($value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a boolean';
						}
						
						break;
						
					/*
					 * STRING
					 */
					case 'string':
						if(!is_string($value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a string';
						}
						
						break;
					
					/*
					 * INTEGER
					 */
					case 'integer':
						if(!is_integer($value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be an integer';
						}
						
						break;
					
					/*
					 * FLOAT
					 */
					case 'float':
						if(!is_float($value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a float';
						}
						
						break;
					
					/*
					 * EMAIL
					 */
					case 'email':
						if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid email address';
						}
						
						break;
						
					/*
					 * PHONE
					 * Phones have the following format: +DD.DDDDDDDD...
					 * The part after the dot has an undefined longitude.
					 */
					case 'phone':
						if(!preg_match('/^\+(\d+)\.(\d+)$/i', $value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid phone number, in +DD.DDDDDDDD... format';
						}
						
						break;
					
					/*
					 * URL
					 */
					case 'url':
						if(!filter_var($value, FILTER_VALIDATE_URL)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid URL';
						}
						
						break;
						
					/*
					 * DOMAIN
					 * Domains must be valid CNAMEs.
					 */
					case 'domain':
						if(!$this->cname(strtolower($value))){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid domain name';
						}
						
						break;
					
					/*
					 * COUNTRY CODE
					 * Country codes must be ISO-3166-1 alfa-2 codes (for example, ES for Spain) 
					 */
					case 'countryCode':
						if(!preg_match("/^([A-Z][A-Z])$/i", $value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid Country Code';
						}
						
						break;
						
					/*
					 * CONTACT ID
					 * Contact IDs must be in DonDominio's format: AA-00000.
					 */
					case 'contactID':
						if(!preg_match("/^([A-Z]+)(-)([0-9]+)$/i", $value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid Contact ID';
						}
						
						break;
					
					/*
					 * DATE
					 * Dates must be specified in YYYYMMDD or YYYY-MM-DD format.
					 */
					case 'date':
						if(!preg_match("/^[0-9]{4}(-?)(0[1-9]|1[0-2])(-?)(0[1-9]|[1-2][0-9]|3[0-1])$/i", $value)){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid date, in YYYYMMDD or YYYY-MM-DD format';
						}
						
						break;
					
					/*
					 * IP v4
					 */
					case 'ipv4':
						if(!filter_var(
							$value,
							FILTER_VALIDATE_IP,
							array('flags' => 
								FILTER_FLAG_IPV4,
								FILTER_FLAG_NO_PRIV_RANGE,
								FILTER_FLAG_NO_RES_RANGE
							))
						){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid IPv4 address';
						}
						
						break;
					
					/*
					 * IP v6
					 */
					case 'ipv6':
						if(!filter_var(
							$value,
							FILTER_VALIDATE_IP,
							array('flags' => 
								FILTER_FLAG_IPV6,
								FILTER_FLAG_NO_PRIV_RANGE,
								FILTER_FLAG_NO_RES_RANGE
							))
						){
							$errors[] = 'Parameter "' . $parameter['name'] . '" must be a valid IPv6 address';
						}
						
						break;
						
					}
				}
			}
		}
		
		return $errors;
	}
	
	/**
	 * Check if a provided string is a valid CNAME.
	 * @param string $cname String to check
	 * @return boolean
	 */
	protected function cname($cname)
	{
		if (!preg_match('/^([a-z0-9\-\.]+)\.[a-z]{2,30}$/i', $cname)){
			return false;
		}

		$double_punctuation = (
			strpos($cname, '..') !== false
			|| strpos($cname, '-.') !== false
			|| strpos($cname, '.-') !== false
		);

		if($double_punctuation){
			return false;
		}

		if(strpos($cname, '.') !== false && strpos($cname, '.') == 0){
			return false;
		}

		if(strpos($cname, '-') !== false && strpos($cname, '-') == 0){
			return false;
		}

		return true;
	}
}

?>