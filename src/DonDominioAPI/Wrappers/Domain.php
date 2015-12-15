<?php

/**
 * Wrapper for the DonDominio Domain API module.
 * Please read the online documentation for more information before using the module.
 *
 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 * @																						@
 * @  Certain calls in this module can use credit from your DonDominio/MrDomain account.	@
 * @  Caution is advised when using calls in this module.									@
 * @																						@
 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 *
 * @link https://dev.dondominio.com/api/docs/api/#section-5
 *
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

require_once( 'DonDominioAPIModule.php' );
 
/**
 * Wrapper for the DonDominio Domain API module.
 */
class DonDominioAPI_Domain extends DonDominioAPIModule
{
	/**
	 * Rewriting the proxy method for specific needs.
	 * @param string $method Method name
	 * @param array $args Array of arguments passed to the method
	 *
	 * @return DonDominioAPIResponse
	 */
	public function proxy( $method, array $args = array())
	{
		if( $method == 'list' ){
			$method = 'getList';
		}
		
		if( !method_exists( $this, $method )){
			trigger_error( 'Method ' . $method . ' not found', E_USER_ERROR );
		}
		
		return call_user_func_array( array( $this, $method ), $args );
	}
	
	/**
	 * Check the availibility of a domain name.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-1
	 *
	 * @param string $domain Domain name to check
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function check( $domain )
	{
		$_params = array( 'domain' => $domain );
		
		$map = array(
			array( 'name'=>'domain', 'type'=>'domain', 'required'=>true )
		);
		
		return $this->execute( 'domain/check/', $_params, $map );
	}
	
	/**
	 * Check if a domain can be transfered.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-2
	 *
	 * @param string $domain Domain name to check
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function checkForTransfer($domain)
	{
		$_params = array('domain' => $domain);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true)
		);
				
		return $this->execute('domain/checkfortransfer/', $_params, $map);
	}
	
	/**
	 * Create a new Domain.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - period 		integer		Number of years to register the domain.
	 * - premium		boolean 	Must be true to register premium domains.
	 * - nameservers	string	 	Comma-separated list of DNS servers (min 2, max 7).
	 *								Use "parking" for redirection & parking service.
	 * ! owner			array		Associative array of owner contact information.
	 * - admin			array		Associative array of administrator contact infromation.
	 * - tech			array		Associative array of technical contact information.
	 * - billing		array		Associative array of billing contact information.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-3
	 *
	 * @param string $domain Domain name to register
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function create($domain, array $args = array())
	{
		$_params = array_merge(
			array('domain' => $domain),
			$this->flattenContacts($args)
		);
		
		$map = array(
			array('name'=>'domain',						'type'=>'domain', 	'required'=>true),
			array('name'=>'period', 					'type'=>'integer', 	'required'=>false),
			array('name'=>'premium', 					'type'=>'boolean', 	'required'=>false),
			array('name'=>'nameservers', 				'type'=>'string', 	'required'=>false),
			
			array('name'=>'ownerContactID',				'type'=>'contactID','required'=>false),
			array('name'=>'ownerContactType', 			'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'ownerContactFirstName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactLastName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactEmail', 			'type'=>'email', 	'required'=>false),
			array('name'=>'ownerContactPhone', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'ownerContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'ownerContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactPostalCode', 	'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactCity', 			'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactState', 			'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactCountry', 		'type'=>'string', 	'required'=>false),
			
			array('name'=>'adminContactID',				'type'=>'contactID','required'=>false),
			array('name'=>'adminContactType', 			'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'adminContactFirstName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactLastName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactEmail', 			'type'=>'email', 	'required'=>false),
			array('name'=>'adminContactPhone', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'adminContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'adminContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactPostalCode', 	'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactCity', 			'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactState', 			'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactCountry', 		'type'=>'string', 	'required'=>false),
			
			array('name'=>'techContactID',				'type'=>'contactID','required'=>false),
			array('name'=>'techContactType', 			'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'techContactFirstName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactLastName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'techContactEmail', 			'type'=>'email', 	'required'=>false),
			array('name'=>'techContactPhone', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'techContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'techContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactPostalCode', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactCity', 			'type'=>'string', 	'required'=>false),
			array('name'=>'techContactState', 			'type'=>'string', 	'required'=>false),
			array('name'=>'techContactCountry', 		'type'=>'string', 	'required'=>false),
			
			array('name'=>'billingContactID',			'type'=>'contactID','required'=>false),
			array('name'=>'billingContactType', 		'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'billingContactFirstName', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactLastName', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactEmail', 		'type'=>'email', 	'required'=>false),
			array('name'=>'billingContactPhone', 		'type'=>'phone', 	'required'=>false),
			array('name'=>'billingContactFax',			'type'=>'phone', 	'required'=>false),
			array('name'=>'billingContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactPostalCode', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactCity', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactState', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactCountry', 		'type'=>'string', 	'required'=>false)
		);
		
		return $this->execute('domain/create/', $_params, $map);
	}
	
	/**
	 * Transfer a domain.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - nameservers	string	 	Comma-separated list of DNS servers (min 2, max 7).
	 *								Use "parking" for redirection & parking service.
	 *								Use "keepns" to leave the DNS servers unmodified.
	 * - authcode		string		Authcode for the Domain, if necessary.
	 * ! owner			array		Associative array of owner contact information.
	 * - admin			array		Associative array of administrator contact infromation.
	 * - tech			array		Associative array of technical contact information.
	 * - billing		array		Associative array of billing contact information.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-4
	 *
	 * @param string $domain Domain name to transfer
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function transfer($domain, array $args = array())
	{
		$_params = array_merge(
			array('domain'=>$domain),
			$this->flattenContacts($args)
		);
		
		$map = array(
			array('name'=>'domain', 					'type'=>'domain', 	'required'=>true),
			array('name'=>'nameservers', 				'type'=>'string', 	'required'=>false),
			array('name'=>'authcode',					'type'=>'string',	'required'=>false),
			
			array('name'=>'ownerContactID',				'type'=>'contactID','required'=>true,	'bypass'=>'ownerContactType'),
			array('name'=>'ownerContactType', 			'type'=>'list', 	'required'=>true, 	'bypass'=>'ownerContactID',		'list'=>array('individual', 'organization')),
			array('name'=>'ownerContactFirstName', 		'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactLastName', 		'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactIdentNumber', 	'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactEmail', 			'type'=>'email', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactPhone', 			'type'=>'phone', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'ownerContactAddress', 		'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactPostalCode', 	'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactCity', 			'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactState', 			'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactCountry', 		'type'=>'string', 	'required'=>true, 	'bypass'=>'ownerContactID'),
			
			array('name'=>'adminContactID',				'type'=>'contactID','required'=>false),
			array('name'=>'adminContactType', 			'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'adminContactFirstName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactLastName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactEmail', 			'type'=>'email', 	'required'=>false),
			array('name'=>'adminContactPhone', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'adminContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'adminContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactPostalCode', 	'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactCity', 			'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactState', 			'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactCountry', 		'type'=>'string', 	'required'=>false),
			
			array('name'=>'techContactID',				'type'=>'contactID','required'=>false),
			array('name'=>'techContactType', 			'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'techContactFirstName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactLastName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'techContactEmail', 			'type'=>'email', 	'required'=>false),
			array('name'=>'techContactPhone', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'techContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'techContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactPostalCode', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactCity', 			'type'=>'string', 	'required'=>false),
			array('name'=>'techContactState', 			'type'=>'string', 	'required'=>false),
			array('name'=>'techContactCountry', 		'type'=>'string', 	'required'=>false),
			
			array('name'=>'billingContactID',			'type'=>'contactID','required'=>false),
			array('name'=>'billingContactType', 		'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'billingContactFirstName', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactLastName', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactEmail', 		'type'=>'email', 	'required'=>false),
			array('name'=>'billingContactPhone', 		'type'=>'phone', 	'required'=>false),
			array('name'=>'billingContactFax',			'type'=>'phone', 	'required'=>false),
			array('name'=>'billingContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactPostalCode', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactCity', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactState', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactCountry', 		'type'=>'string', 	'required'=>false)
		);
		
		return $this->execute('domain/transfer/', $_params, $map);
	}
	
	/**
	 * Update domain parameters, such as contacts, nameservers, and more.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * [updateType = contact]
	 * - owner			array		Associative array of owner contact information.
	 * - admin			array		Associative array of administrator contact infromation.
	 * - tech			array		Associative array of technical contact information.
	 * - billing		array		Associative array of billing contact information.
	 *
	 * [updateType = nameservers]
	 * ! nameservers	string	 	Comma-separated list of DNS servers (min 2, max 7).
	 *								Use "default" to assign DonDominio/MrDomain hosting values.
	 *
	 * [updateType = transferBlock]
	 * ! transferBlock	boolean		Enables or disables the transfer block for the domain.
	 *
	 * [updateType = block]
	 * ! block			boolean		Enables or disables the domain block.
	 *
	 * [updateType = whoisPrivacy]
	 * ! whoisPrivacy	boolean		Enables or disables the whoisPrivacy service for the domain.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-5
	 *
	 * @param string $domain Domain name to update
	 * @param string $updateType Type of information to modify (contact, nameservers, transferBlock, block, whoisPrivacy)
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function update( $domain, array $args = array())
	{
		$_params = array_merge(
			$this->getDomainOrDomainID( $domain ),
			$this->flattenContacts( $args )
		);
		
		$map = array(
			array( 'name' => 'domain', 						'type' => 'domain', 	'required' => true, 	'bypass' => 'domainID' ),
			array( 'name' => 'domainID',					'type' => 'string', 	'required' => true, 	'bypass' => 'domain' ),
			array( 'name' => 'updateType',					'type' => 'list',		'required' => true,		'list' => array( 'contact', 'nameservers', 'transferBlock', 'block', 'whoisPrivacy', 'renewalMode' )),
			
			array( 'name' => 'ownerContactID',				'type' => 'contactID',	'required' => false,	'bypass' => 'ownerContactType' ),
			array( 'name' => 'ownerContactType', 			'type' => 'list', 		'required' => false, 	'bypass' => 'ownerContactID',		'list' => array( 'individual', 'organization' )),
			array( 'name' => 'ownerContactFirstName', 		'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactLastName', 		'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactOrgName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'ownerContactOrgType', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'ownerContactIdentNumber', 	'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactEmail', 			'type' => 'email', 		'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactPhone', 			'type' => 'phone', 		'required' => false , 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactFax', 			'type' => 'phone', 		'required' => false),
			array( 'name' => 'ownerContactAddress', 		'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactPostalCode', 		'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactCity', 			'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactState', 			'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			array( 'name' => 'ownerContactCountry', 		'type' => 'string', 	'required' => false, 	'bypass' => 'ownerContactID' ),
			
			array( 'name' => 'adminContactID',				'type' => 'contactID',	'required' => false ),
			array( 'name' => 'adminContactType', 			'type' => 'list', 		'required' => false, 	'list' => array( 'individual', 'organization' )),
			array( 'name' => 'adminContactFirstName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactLastName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactOrgName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactOrgType', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactIdentNumber', 	'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactEmail', 			'type' => 'email', 		'required' => false ),
			array( 'name' => 'adminContactPhone', 			'type' => 'phone', 		'required' => false ),
			array( 'name' => 'adminContactFax', 			'type' => 'phone', 		'required' => false ),
			array( 'name' => 'adminContactAddress', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactPostalCode', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactCity', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactState', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'adminContactCountry', 		'type' => 'string', 	'required' => false ),
			
			array( 'name' => 'techContactID',				'type' => 'contactID',	'required' => false ),
			array( 'name' => 'techContactType', 			'type' => 'list', 		'required' => false, 	'list' => array( 'individual', 'organization' )),
			array( 'name' => 'techContactFirstName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactLastName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactOrgName', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactOrgType', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactIdentNumber', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactEmail', 			'type' => 'email', 		'required' => false ),
			array( 'name' => 'techContactPhone', 			'type' => 'phone', 		'required' => false ),
			array( 'name' => 'techContactFax', 				'type' => 'phone', 		'required' => false ),
			array( 'name' => 'techContactAddress', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactPostalCode', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactCity', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactState', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'techContactCountry', 			'type' => 'string', 	'required' => false ),
			
			array( 'name' => 'billingContactID',			'type' => 'contactID',	'required' => false ),
			array( 'name' => 'billingContactType', 			'type' => 'list', 		'required' => false, 	'list' => array( 'individual', 'organization' )),
			array( 'name' => 'billingContactFirstName', 	'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactLastName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactOrgName', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactOrgType', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactIdentNumber', 	'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactEmail', 		'type' => 'email', 		'required' => false ),
			array( 'name' => 'billingContactPhone', 		'type' => 'phone', 		'required' => false ),
			array( 'name' => 'billingContactFax',			'type' => 'phone', 		'required' => false ),
			array( 'name' => 'billingContactAddress', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactPostalCode', 	'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactCity', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactState', 		'type' => 'string', 	'required' => false ),
			array( 'name' => 'billingContactCountry', 		'type' => 'string', 	'required' => false ),
			
			array( 'name' => 'nameservers',					'type' => 'string',		'required' => false ),
			array( 'name' => 'transferBlock',				'type' => 'boolean',	'required' => false ),
			array( 'name' => 'block',						'type' => 'boolean',	'required' => false ),
			array( 'name' => 'whoisPrivacy',				'type' => 'boolean',	'required' => false ),
			array( 'name' => 'renewalMode',					'type' => 'list',		'required' => false,	'list' => array( 'autorenew', 'manual', 'letexpire' ))
		);
		
		return $this->execute( 'domain/update/', $_params, $map );
	}
	
	/**
	 * Modify nameservers for a domain.
	 * This method expects an array of nameservers that should look like this:
	 *
	 * $nameservers = array('ns1.dns.com', 'ns2.dns.com)
	 *
	 * @link https://docs.dondominio.com/api/#section-5-6
	 *
	 * @param string $domain Domain name or Domain ID to be modified
	 * @param array $nameservers Array containing the nameservers
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function updateNameServers( $domain, array $nameservers = array())
	{
		$_params = array_merge(
			$this->getDomainOrDomainID( $domain ),
			array( 'nameservers' => implode( ',', $nameservers ))
		);
		
		$map = array(
			array( 'name' => 'domain',			'type' => 'domain',		'required' => true,		'bypass' => 'domainID' ),
			array( 'name' => 'domainID',		'type' => 'string',		'required' => true,		'bypass' => 'domain' ),
			array( 'name' => 'nameservers',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute('domain/updatenameservers/', $_params, $map);
	}
	
	/**
	 * Modify contact information for a domain.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - owner			array		Associative array of owner contact information.
	 * - admin			array		Associative array of administrator contact infromation.
	 * - tech			array		Associative array of technical contact information.
	 * - billing		array		Associative array of billing contact information.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-7
	 *
	 * @param string $domain Domain name or Domain ID to be modified
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function updateContacts($domain, array $args = array())
	{	
		$_params = array_merge(
			$this->getDomainOrDomainID($domain),
			$this->flattenContacts($args)
		);
		
		$map = array(
			array('name'=>'domain', 					'type'=>'domain', 	'required'=>true, 	'bypass'=>'domainID'),
			array('name'=>'domainID',					'type'=>'string', 	'required'=>true, 	'bypass'=>'domain'),
			
			array('name'=>'ownerContactID',				'type'=>'contactID','required'=>false,	'bypass'=>'ownerContactType'),
			array('name'=>'ownerContactType', 			'type'=>'list', 	'required'=>false, 	'bypass'=>'ownerContactID',		'list'=>array('individual', 'organization')),
			array('name'=>'ownerContactFirstName', 		'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactLastName', 		'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'ownerContactIdentNumber', 	'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactEmail', 			'type'=>'email', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactPhone', 			'type'=>'phone', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'ownerContactAddress', 		'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactPostalCode', 	'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactCity', 			'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactState', 			'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			array('name'=>'ownerContactCountry', 		'type'=>'string', 	'required'=>false, 	'bypass'=>'ownerContactID'),
			
			array('name'=>'adminContactID',				'type'=>'contactID','required'=>false),
			array('name'=>'adminContactType', 			'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'adminContactFirstName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactLastName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactEmail', 			'type'=>'email', 	'required'=>false),
			array('name'=>'adminContactPhone', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'adminContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'adminContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactPostalCode', 	'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactCity', 			'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactState', 			'type'=>'string', 	'required'=>false),
			array('name'=>'adminContactCountry', 		'type'=>'string', 	'required'=>false),
			
			array('name'=>'techContactID',				'type'=>'contactID','required'=>false),
			array('name'=>'techContactType', 			'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'techContactFirstName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactLastName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'techContactEmail', 			'type'=>'email', 	'required'=>false),
			array('name'=>'techContactPhone', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'techContactFax', 			'type'=>'phone', 	'required'=>false),
			array('name'=>'techContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactPostalCode', 		'type'=>'string', 	'required'=>false),
			array('name'=>'techContactCity', 			'type'=>'string', 	'required'=>false),
			array('name'=>'techContactState', 			'type'=>'string', 	'required'=>false),
			array('name'=>'techContactCountry', 		'type'=>'string', 	'required'=>false),
			
			array('name'=>'billingContactID',			'type'=>'contactID','required'=>false),
			array('name'=>'billingContactType', 		'type'=>'list', 	'required'=>false, 	'list'=>array('individual', 'organization')),
			array('name'=>'billingContactFirstName', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactLastName', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactOrgName', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactOrgType', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactIdentNumber', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactEmail', 		'type'=>'email', 	'required'=>false),
			array('name'=>'billingContactPhone', 		'type'=>'phone', 	'required'=>false),
			array('name'=>'billingContactFax',			'type'=>'phone', 	'required'=>false),
			array('name'=>'billingContactAddress', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactPostalCode', 	'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactCity', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactState', 		'type'=>'string', 	'required'=>false),
			array('name'=>'billingContactCountry', 		'type'=>'string', 	'required'=>false)
		);
		
		return $this->execute('domain/updatecontacts/', $_params, $map);
	}
	
	/**
	 * Creates a DNS record associated to a domain (gluerecord).
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! ipv4			IPv4		IPv4 address for the DNS server
	 * - ipv6			IPv6		IPv6 address for the DNS server
	 *
	 * @link https://docs.dondominio.com/api/#section-5-8
	 *
	 * @param string $domain Domain name or Domain ID to be modified
	 * @param string $name Name of the gluerecord to be created
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function glueRecordCreate($domain, array $args = array())
	{
		$_params = array_merge(
			$this->getDomainOrDomainID($domain),
			$args
		);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
			array('name'=>'name', 'type'=>'name', 'required'=>true),
			array('name'=>'ipv4', 'type'=>'ipv4', 'required'=>true),
			array('name'=>'ipv6', 'type'=>'ipv6', 'required'=>false)
		);
		
		return $this->execute('domain/gluerecordcreate/', $_params, $map);
	}
	
	/**
	 * Modifies an existing gluerecord for a domain.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! ipv4			IPv4		IPv4 address for the DNS server
	 * - ipv6			IPv6		IPv6 address for the DNS server
	 *
	 * @link https://docs.dondominio.com/api/#section-5-9
	 *
	 * @param string $domain Domain name or Domain ID to be modified
	 * @param string $name Name of the gluerecord to be updated
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function glueRecordUpdate($domain, array $args = array())
	{
		$_params = array_merge(
			$this->getDomainOrDomainID($domain),
			$args
		);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
			array('name'=>'name', 'type'=>'name', 'required'=>true),
			array('name'=>'ipv4', 'type'=>'ipv4', 'required'=>true),
			array('name'=>'ipv6', 'type'=>'ipv6', 'required'=>false)
		);
		
		return $this->execute('domain/gluerecordupdate/', $_params, $map);
	}
	
	/**
	 * Deletes an existing gluerecord for a domain.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-10
	 *
	 * @param string $domain Domain name or Domain ID to be modified
	 * @param string $name Name of the gluerecord to be deleted
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function glueRecordDelete($domain, array $args = array())
	{
		$_params = array_merge(
			$this->getDomainOrDomainID($domain),
			$args
		);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
			array('name'=>'name', 'type'=>'name', 'required'=>true)
		);
		
		return $this->execute('domain/gluerecorddelete/', $_params, $map);
	}
	
	/**
	 * List the domains in the account, filtered by various parameters.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Max results (defaults to 1000)
	 * - page			integer		Number of the page to get (defaults to 1)
	 * - domain			string		Exact domain name to find
	 * - word			string		Filter the list by this string
	 * - tld			string		Filter list by this TLD
	 * - renewable		boolean		Set to true to get only renewable domains
	 *
	 * @link https://docs.dondominio.com/api/#section-5-11
	 *
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function getList(array $args = array())
	{
		$_params = $args;
		
		$map = array(
			array('name'=>'pageLength', 'type'=>'integer', 'required'=>false),
			array('name'=>'page', 'type'=>'integer', 'required'=>false),
			array('name'=>'domain', 'type'=>'domain', 'required'=>false),
			array('name'=>'word', 'type'=>'string', 'required'=>false),
			array('name'=>'tld', 'type'=>'string', 'required'=>false),
			array('name'=>'renewable', 'type'=>'boolean', 'required'=>false)
		);
		
		return $this->execute('domain/list/', $_params, $map);
	}
	
	/**
	 * Get information from a domain in the account.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - infoType		string		Type of information to get. Accepted values:
	 *								status, contact, nameservers, authcode, service, gluerecords.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-12
	 *
	 * @param string $domain Domain name or Domain ID to get the information from
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function getInfo($domain, array $args = array())
	{
		$_params = array_merge(
			$this->getDomainOrDomainID($domain),
			$args
		);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
			array('name'=>'infoType', 'type'=>'list', 'required'=>true, 'list'=>array('status', 'contact', 'nameservers', 'authcode', 'service', 'gluerecords'))
		);
		
		return $this->execute('domain/getinfo/', $_params, $map);
	}
	
	/**
	 * Get the authcode for a domain in the account.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-13
	 *
	 * @param string $domain Domain name or Domain ID to get the authcode for
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function getAuthCode($domain)
	{
		$_params = $this->getDomainOrDomainID($domain);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
		);
		
		return $this->execute('domain/getauthcode/', $_params, $map);
	}
	
	/**
	 * Get the nameservers for a domain in the account.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-14
	 *
	 * @param string $domain Domain name or Domain ID to get the nameservers for
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function getNameServers($domain)
	{
		$_params = $this->getDomainOrDomainID($domain);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
		);
		
		return $this->execute('domain/getnameservers/', $_params, $map);
	}
	
	/**
	 * Get the gluerecords for a domain in the account.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-15
	 *
	 * @param string $domain Domain name or Domain ID to get the gluerecords for
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function getGlueRecords($domain)
	{
		$_params = $this->getDomainOrDomainID($domain);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
		);
		
		return $this->execute('domain/getgluerecords/', $_params, $map);
	}
	
	/**
	 * Attempts to renew a domain in the account.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - period		integer		Number of years to renew the domain for
	 *
	 * @link https://docs.dondominio.com/api/#section-5-16
	 *
	 * @param string $domain Domain name or Domain ID to renew
	 * @param string $curExpDate Current expiration date for this domain
	 * @param array $args Associative array of parameters
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function renew($domain, array $args = array())
	{
		$_params = array_merge(
			$this->getDomainOrDomainID($domain),
			$args
		);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true, 'bypass'=>'domainID'),
			array('name'=>'domainID', 'type'=>'string', 'required'=>true, 'bypass'=>'domain'),
			array('name'=>'curExpDate', 'type'=>'date', 'required'=>true),
			array('name'=>'period', 'type'=>'integer', 'required'=>false)
		);
		
		return $this->execute('domain/renew/', $_params, $map);
	}
	
	/**
	 * Performs a whois lookup for a domain name.
	 * Returns whois data for a domain in a single string field. By default,
	 * only domains on the user account can be queried.
	 *
	 * @link https://docs.dondominio.com/api/#section-5-17
	 *
	 * @param string $domain Domain name to be queried
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function whois($domain)
	{
		$_params = array('domain'=>$domain);
		
		$map = array(
			array('name'=>'domain', 'type'=>'domain', 'required'=>true)
		);
		
		return $this->execute('domain/whois/', $_params, $map);
	}
	
	/**
	 * Check whether the domain is a domain name or a domain ID.
	 *
	 * Some calls can be provided with a domain name or a domain ID. To simplify
	 * methods, we only ask for one parameter and we try to identify if it's a
	 * Domain Name or a Domain ID.
	 *
	 * If we can find a dot (.) inside $domain, then it's a Domain Name. If not,
	 * we assume it's a Domain ID.
	 *
	 * This method returns an array ready to be passed to any API call or to be
	 * merged with more parameters.
	 *
	 * @param string $domain Domain name or Domain ID
	 *
	 * @return array
	 */
	protected function getDomainOrDomainID($domain)
	{
		if(strpos($domain, '.')){
			return array('domain' => $domain);
		}else{
			return array('domainID' => $domain);
		}
	}
	
	/**
	 * Convert a multi-dimensional array of parameters (w/ contacts) to its
	 * flattened equivalent.
	 *
	 * 		We take an array that looks like this:
	 *			Array => [
	 *				'domain' => 'example.com',
	 *				'owner' => [
	 *					'firstName' => 'John',
	 *					'lastName' => 'Doe'
	 *				]
	 *			]
	 *
	 *		And flatten it so it looks like this:
	 *			Array => [
	 *				'domain' => 'example.com',
	 *				'ownerContactFirstName' => 'John',
	 *				'ownerContactLastName' => 'Doe'
	 *			]
	 *
	 * @param array $args Associative array of arguments
	 * @return array Flattened array
	 */
	protected function flattenContacts(array $args = array())
	{
		$_params = array();
		
		foreach($args as $section=>$argument){
			//We only parse arrays inside the array that match one of the four contact types
			if(is_array($argument) && in_array($section, array('owner', 'admin', 'billing', 'tech'))){
				foreach($argument as $key=>$value){
					//Automagically add the 'Contact' word to the key
					$_params[$section . 'Contact' . ucfirst($key)] = $value;
				}
			}else{
				$_params[$section] = $argument;
			}
		}
		
		//The flattened array
		return $_params;
	}
}

?>