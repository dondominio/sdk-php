<?php

/**
 * Wrapper for the DonDominio Account API module.
 * Please read the online documentation for more information before using the module.
 *
 * @link https://dev.dondominio.com/api/docs/api/#section-4
 * 
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

require_once('DonDominioAPIModule.php');

/**
 * Wrapper for the DonDominio Account API module.
 */
class DonDominioAPI_Account extends DonDominioAPIModule
{
	/**
	 * Get the account information.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#info-account-info
	 *
	 * @return DonDominioResponse
	 */
	protected function info()
	{
		return $this->execute('account/info/');
	}
	
	/**
	 * Gets the zones (TLDs) available with prices and extra information.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Max results (defaults to 1000)
	 * - page			integer		Number of the page to get (defaults to 1)
	 * - tld			string		Filter zones by TLD
	 * - tldtop			string		Filter zones by top TLD
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#zones-account-zones
	 *
	 * @return DonDominioResponse
	 */
	protected function zones(array $args = array())
	{
		$map = array(
			array('name'=>'pageLength', 'type'=>'integer', 'required'=>false),
			array('name'=>'page', 'type'=>'integer', 'required'=>false),
			array('name'=>'tld', 'type'=>'string', 'required'=>false),
			array('name'=>'tldtop', 'type'=>'string', 'required'=>false)
		);
		
		return $this->execute('account/zones', $args, $map);
	}
}