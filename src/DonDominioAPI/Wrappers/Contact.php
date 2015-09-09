<?php

/**
 * Wrapper for the DonDominio Contact API module.
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

require_once('DonDominioAPIModule.php');

/**
 * Wrapper for the DonDominio Contact API module.
 */
class DonDominioAPI_Contact extends DonDominioAPIModule
{	
	/**
	 * Rewriting the proxy method for specific needs.
	 * @param string $method Method name
	 * @param array $args Array of arguments passed to the method
	 * @return DonDominioResponse
	 */
	public function proxy($method, array $args = array())
	{
		if($method == 'list'){
			$method = 'getList';
		}
		
		if(!method_exists($this, $method)){
			trigger_error('Method ' . $method . ' not found', E_USER_ERROR);
		}
		
		return call_user_func_array(array($this, $method), $args);
	}
	
	/**
	 * List the contacts in the account, filtered by various parameters.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Max results (defaults to 1000)
	 * - page			integer		Number of the page to get (defaults to 1)
	 * - name			string		Filter contacts by name
	 * - email			string		Filter contacts by email
	 * - country		string		Filter contacts by country code
	 * - identNumber	string		Filter contacts by ID number
	 *
	 * @link https://docs.dondominio.com/api/#section-6-1
	 *
	 * @param array $args Associative array of parameters
	 * @return DonDominioResponse
	 */
	protected function getList(array $args = array())
	{
		$map = array(
			array('name'=>'pageLength', 'type'=>'integer', 'required'=>false),
			array('name'=>'page', 'type'=>'integer', 'required'=>false),
			array('name'=>'name', 'type'=>'string', 'required'=>false),
			array('name'=>'email', 'type'=>'email', 'required'=>false),
			array('name'=>'country', 'type'=>'countryCode', 'required'=>false),
			array('name'=>'identNumber', 'type'=>'string', 'required'=>false)
		);
		
		return $this->execute('contact/list/', $args, $map);
	}
	
	/**
	 * Get all available information for a contact.
	 *
	 * @link https://docs.dondominio.com/api/#section-6-2
	 *
	 * @param string $contactID Contact's ID code
	 * @param string $infoType Type of information to get.
	 * @return DonDominioResponse
	 */
	protected function getInfo($contactID, $infoType='data')
	{
		$_params = array('contactID'=>$contactID, 'infoType'=>$infoType);
		
		$map = array(
			array('name'=>'contactID', 'type'=>'contactID', 'required'=>true),
			array('name'=>'infoType', 'type'=>'list', 'required'=>false, 'list'=>array('data'))
		);
		
		return $this->execute('contact/getinfo/', $_params, $map);
	}
}

?>