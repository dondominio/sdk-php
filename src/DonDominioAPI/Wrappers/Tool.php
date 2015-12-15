<?php

/**
 * Wrapper for the DonDominio Tool API module.
 * Please read the online documentation for more information before using the module.
 *
 * @link https://dev.dondominio.com/api/docs/api/#section-3
 * 
 * @package DonDominioPHP
 * @subpackage Wrappers
 */
 
require_once('DonDominioAPIModule.php');

/**
 * Wrapper for the DonDominio Tool API module.
 */
class DonDominioAPI_Tool extends DonDominioAPIModule
{
	/**
	 * Tests the connectivity to the API.
	 *
	 * @link https://docs.dondominio.com/api/#section-3-1
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function hello()
	{
		return $this->execute('tool/hello/', $_params);
	}
	
	/**
	 * Converts a string or domain name to unicode or punycode (IDNA).
	 *
	 * @link https://docs.dondominio.com/api/#section-3-2
	 * @link http://en.wikipedia.org/wiki/Internationalized_domain_name#Example_of_IDNA_encoding
	 *
	 * @param string $query String to be converted
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function idnConverter($query)
	{
		if(empty($query)){
			$query = '';
		}
		
		$_params = array('query'=>$query);
		
		$map = array(
			array('name'=>'query',			'type'=>'string',	'required'=>true)
		);
		
		return $this->execute('tool/idnconverter/', $_params, $map);
	}
	
	/**
	 * Generates suggestions for domains based on a word.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! query				string		The word to search for
	 * - language			string		One of: es, en, zh, fr, de, kr, pt, tr (defaults to es)
	 * - tlds				string		One of: com, net, tv, cc, es, org, info, biz, eu (defaults to "com,net,tv,es"); separate with commas
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#section-3-3
	 *
	 * @param	array		$args		Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function domainSuggests( array $args = array())
	{
		$_params = $args;
		
		$map = array(
			array( 'name' => 'query',		'type' => 'string',		'required' => true ),
			array( 'name' => 'language',	'type' => 'string',		'required' => false ),
			array( 'name' => 'tlds',		'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'tool/domainsuggests/', $_params, $map );
	}
	
	/**
	 * Get various types of code tables used by the API.
	 *
	 * @link https://docs.dondominio.com/api/#section-3-4
	 *
	 * @param string $tableType Table to get
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function getTable($tableType)
	{
		if(empty($tableType)){
			$tableType = '';
		}
		
		$_params = array('tableType'=>$tableType);
		
		$map = array(
			array('name'=>'tableType',		'type'=>'list',		'required'=>true,		'list'=>array('countries', 'es_juridic'))
		);
		
		return $this->execute('tool/gettable/', $_params, $map);
	}
	
	/**
	 * Decode the parameters contained in a CSR.
	 *
	 * @link https://docs.dondominio.com/api/#section-3-5
	 * @link http://en.wikipedia.org/wiki/Certificate_signing_request
	 *
	 * @param string $csrData CSR data (including ---BEGIN--- and ---END---)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function csrDecode($csrData)
	{
		if(empty($csrData)){
			$csrData = '';
		}
		
		$_params = array('csrData'=>$csrData);
		
		$map = array(
			array('name'=>'csrData',		'type'=>'string',	'required'=>true)
		);
		
		return $this->execute('tool/csrdecode/', $_params, $map);
	}
	
	/**
	 * Test the DNS servers for a domain using the Domain Information Groper.
	 * Accepts an associative array with the following parameters:
	 *
	 * @link https://docs.dondominio.com/api/#section-3-6
	 * @link https://en.wikipedia.org/wiki/Dig_(command)
	 *
	 * ! = required
	 * ! query			string		Domain/query to test
	 * ! type			list		One of: A, AAAA, ANY, CNAME, MX, NS, SOA, or TXT
	 * ! nameserver		IPv4		DNS server to use to test the domain
	 *
	 * @param array $args Associative array of parameters
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dig(array $args = array())
	{
		$map = array(
			array('name' => 'query',		'type' => 'string', 'required' => true),
			array('name' => 'type',			'type' => 'list',	'required' => true,		'list' => array('A', 'AAAA', 'ANY', 'CNAME', 'MX', 'NS', 'SOA', 'TXT')),
			array('name' => 'nameserver',	'type' => 'ipv4',	'required' => true)
		);
		
		return $this->execute('tool/dig/', $args, $map);
	}
	
	/**
	 * Checks the domain zone.
	 * Accepts an associative array with the following parameters:
	 *
	 * @link https://docs.dondominio.com/api/#section-3-7
	 *
	 * ! = required
	 * ! nameservers	string	 	Comma-separated list of DNS servers (min 2, max 7).
	 *
	 * @param string $domain Domain to be checked
	 * @param array $args Associative array of parameters
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function zonecheck($domain, array $args = array())
	{
		$_params = array_merge(
			array('domain' => $domain),
			$args
		);
		
		$map = array(
			array('name' => 'domain',		'type' => 'domain', 'required' => true),
			array('name' => 'nameservers',	'type' => 'string', 'required' => true)
		);
		
		return $this->execute('tool/zonecheck/', $_params, $map);
	}
}

?>