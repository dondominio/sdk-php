<?php

/**
 * Wrapper for the DonDominio Service API module.
 * Please read the online documentation for more information before using the module.
 *
 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 * @																						@
 * @  Certain calls in this module can use credit from your DonDominio/MrDomain account. 	@
 * @  Caution is advised when using calls in this module.									@
 * @																						@
 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 *
 * @link https://dev.dondominio.com/api/docs/api/
 *
 * @package DonDominioPHP
 * @subpackage Wrappers
 */
 
require_once( 'DonDominioAPIModule.php' );

/**
 * Wrapper for the DonDominio Service API module.
 */
class DonDominioAPI_Service extends DonDominioAPIModule
{
	/**
	 * Rewriting the proxy method for specific needs.
	 *
	 * @param	string		$method				Method name
	 * @param	array		$args				Array of arguments passed to the method
	 *
	 * @return	DonDominioAPIResponse
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
	 * Lists/Searchs services and hostings on the account.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - word			string		Filter by this word
	 * - tld			string		Filter list by this TLD
	 * - renewable		boolean		Filter results by renew period status
	 * - status			string		Filter results by specific status ( init, active, inactive, renewed, renewable )
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#list-service-list
	 *
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function getList( array $args = array())
	{
		$_params = $args;
		
		$map = array(
			array( 'name' => 'pageLength', 		'type' => 'integer', 	'required' => false ),
			array( 'name' => 'page', 			'type' => 'integer', 	'required' => false ),
			array( 'name' => 'name', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'word', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'tld', 			'type' => 'string', 	'required' => false ),
			array( 'name' => 'renewable', 		'type' => 'boolean', 	'required' => false ),
			array( 'name' => 'status', 			'type' => 'list', 		'required' => false,	'list' => array( 'init', 'active', 'inactive', 'renewed', 'renewable' ))
		);
		
		return $this->execute( 'service/list/', $_params, $map );
	}
	
	/**
	 * Retrieves information about a service/hosting.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! infoType		string		One of: status, resources, serverinfo
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#get-info-service-getinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function getInfo( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName', 	'type' => 'string', 	'required' => true ),
			array( 'name' => 'infoType', 		'type' => 'list',		'required' => true, 	'list' => array( 'status', 'resources', 'serverinfo' ))
		);
		
		return $this->execute( 'service/getinfo/', $_params, $map );
	}
	
	/**
	 * Creates or associates a new hosting service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! productKey		string		One of: redir, mini, mail, mailplus, mailpro, basic, professional, advanced, corporate
	 * - period			integer		Duration, in years, of the service
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#create-service-create
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function create( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'productKey',		'type' => 'list',		'required' => true,		'list' => array( 'redir', 'mini', 'mail', 'mailplus', 'mailpro', 'basic', 'professional', 'advanced', 'corporate' )),
			array( 'name' => 'period',			'type' => 'integer',	'required' => false )
		);
		
		return $this->execute( 'service/create/', $_params, $map );
	}
	
	/**
	 * Renews an existing hosting service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - period			integer		Period, in years, to renew the service for (defaults to 1 year)
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#renew-service-renew
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function renew( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'period',			'type' => 'integer',	'required' => false )
		);
		
		return $this->execute( 'service/renew/', $_params, $map );
	}
	
	/**
	 * Upgrades the service to a higher plan.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! productKey		string		One of: redir, mini, mail, mailplus, mailpro, basic, professional, advanced, corporate
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#upgrade-service-upgrade
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of arguments (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function upgrade( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'productKey',		'type' => 'list',		'required' => true,		'list' => array( 'redir', 'mini', 'mail', 'mailplus', 'mailpro', 'basic', 'professional', 'advanced', 'corporate' ))
		);
		
		return $this->execute( 'service/upgrade/', $_params, $map );
	}
	
	/**
	 * Modifies global parameters of an existing service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! updateType		string		One of: renewalMode
	 * - renewalMode	string		One of: autorenew, manual
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#update-service-update
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function update( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'updateType',		'type' => 'list',		'required' => true,		'list' => array( 'renewalMode' )),
			array( 'name' => 'renewalMode',		'type' => 'list',		'required' => false,	'list' => array( 'autorenew', 'manual' ))
		);
		
		return $this->execute( 'service/update/', $_params, $map );
	}
	
	/**
	 * Retrieves information about a parking service.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#parking-get-info-service-parkinggetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function parkingGetInfo( $serviceName )
	{
		$_params = array( 'serviceName' => $serviceName );
		
		$map = array(
			array( 'name' => 'serviceName', 	'type' => 'text', 		'required' => true )
		);
		
		return $this->execute( 'service/parkinggetinfo/', $_params, $map );
	}
	
	/**
	 * Modifies parameters from the Parking Service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! enabled		boolean			Enable/Disable parking service
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#parking-update-service-parkingupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function parkingUpdate( $serviceName, array $args = array())
	{
		$_params = array_merge(
			array( 'serviceName' => $serviceName ),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'enabled',			'type' => 'boolean',	'required' => true )
		);
		
		return $this->execute( 'service/parkingupdate/', $_params, $map );
	}
	
	/**
	 * Retrieves the URL needed to log in the Webconstructor service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! subdomain		string			The subdomain associated with the Webconstructor
	 * - loginlang		string			Default language for the Webconstructor interface
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#webconstructor-login-service-webconstructorlogin
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function webConstructorLogin( $serviceName, array $args = array())
	{
		$_params = array_merge(
			array( 'serviceName' => $serviceName ),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'subdomain',		'type' => 'text',		'required' => true ),
			array( 'name' => 'loginlang',		'type' => 'text',		'required' => false )
		);
		
		return $this->execute( 'service/webconstructorlogin/', $_params, $map );
	}
	
	/**
	 * Lists/Searchs FTP accounts under the specified service.
	 * Accepts an associative array with the following parameters:
	 * 
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - filter			string		Filter results by this text
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#ftp-list-service-ftplist
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function ftpList( $serviceName, array $args = array())
	{
		$_params = array_merge(
			array( 'serviceName' => $serviceName ),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'pageLength',		'type' => 'integer',	'required' => false ),
			array( 'name' => 'page',			'type' => 'integer',	'required' => false ),
			array( 'name' => 'filter',			'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/ftplist/', $_params, $map );
	}
	
	/**
	 * Retrieves information about an FTP account.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#ftp-get-info-service-ftpgetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function ftpGetInfo( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/ftpgetinfo/', $_params, $map );
	}
	
	/**
	 * Creates a new FTP account.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! name			string		Name of the account (username)
	 * ! ftpPath		string		Path (directory) for this account
	 * ! password		string		Password for the username
	 * ! quota			integer		Quota, in bytes, for the account. Min: 1MB
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#ftp-create-service-ftpcreate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function ftpCreate( $serviceName, array $args = array())
	{
		$_params = array_merge(
			array( 'serviceName' => $serviceName ),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'name',			'type' => 'string',		'required' => true ),
			array( 'name' => 'ftpPath',			'type' => 'string',		'required' => true ),
			array( 'name' => 'password',		'type' => 'string',		'required' => true ),
			array( 'name' => 'quota',			'type' => 'integer',	'required' => true,		'min' => 1048576 )
		);
		
		return $this->execute( 'service/ftpcreate/', $_params, $map );
	}
	
	/**
	 * Updates settings and parameters of an existing FTP account.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! ftpPath		string		Path (directory) for this account
	 * ! password		string		Password for the username
	 * ! quota			integer		Quota, in bytes, for the account. Min: 1MB
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#ftp-update-service-ftpupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function ftpUpdate( $serviceName, $entityID, array $args = array())
	{
		$_params = array_merge( 
			array(
				'serviceName' => $serviceName,
				'entityID' => $entityID 
			),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true ),
			array( 'name' => 'ftpPath',			'type' => 'string',		'required' => true ),
			array( 'name' => 'password',		'type' => 'string',		'required' => true ),
			array( 'name' => 'quota',			'type' => 'integer',	'required' => true,		'min' => 1048576 )
		);
		
		return $this->execute( 'service/ftpupdate/', $_params, $map );
	}
	
	/**
	 * Deletes an existing FTP account.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#ftp-delete-service-ftpdelete
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return DonDominioAPIResponse
	 */
	protected function ftpDelete( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/ftpdelete/', $_params, $map );
	}
	
	/**
	 * Searchs/lists databases in an specific service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - filter			string		Filter results by this text
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#database-list-service-ddbblist
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function ddbbList( $serviceName, array $args = array())
	{
		$_params = array_merge(
			array( 'serviceName' => $serviceName ),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'pageLength',		'type' => 'integer',	'required' => false ),
			array( 'name' => 'page',			'type' => 'integer',	'required' => false,	'min' => 1 ),
			array( 'name' => 'filter',			'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/ddbblist/', $_params, $map );
	}
	
	/**
	 * Retrieves information from an existing Database.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#database-get-info-service-ddbbgetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function ddbbGetInfo( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/ddbbgetinfo/', $_params, $map );
	}
	
	/**
	 * Creates a new Database.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! password			string		Password for the Database account
	 * - externalAccess		boolean		Enable/Disable external access to the database (disabled by default)
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#database-create-service-ddbbcreate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function ddbbCreate( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'password',		'type' => 'string',		'required' => true ),
			array( 'name' => 'externalAccess',	'type' => 'boolean',	'required' => false )
		);
		
		return $this->execute( 'service/ddbbcreate/', $_params, $map );
	}
	
	/**
	 * Modifies settings and parameters for an existing Database.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! password			string		Set password for the database account
	 * - externalAccess		boolean		Enable/disable external access for the database (disabled by default)
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#database-update-service-ddbbupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function ddbbUpdate( $serviceName, $entityID, array $args = array())
	{
		$_params = array_merge(
			array(
				'serviceName' => $serviceName,
				'entityID' => $entityID
			),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true ),
			array( 'name' => 'password',		'type' => 'string',		'required' => true ),
			array( 'name' => 'externalAccess',	'type' => 'boolean',	'required' => false )
		);
		
		return $this->execute( 'service/ddbbupdate/', $_params, $map );
	}
	
	/**
	 * Deletes a Database.
	 * This, of course, drops all database information. Use with caution.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#database-delete-service-ddbbdelete
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function ddbbDelete( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/ddbbdelete/', $_params, $map );
	}
	
	/**
	 * Searchs/Lists subdomains in an specific service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - filter			string		Filter results by this text
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#subdomain-list-service-subdomainlist
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function subdomainList( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'pageLength',		'type' => 'integer',	'required' => false ),
			array( 'name' => 'page',			'type' => 'integer',	'required' => false,	'min' => 1 ),
			array( 'name' => 'filter',			'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/subdomainlist/', $_params, $map );
	}
	
	/**
	 * Retrieves information from an existing subdomain.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#subdomain-get-info-service-subdomaingetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function subdomainGetInfo( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/subdomaingetinfo/', $_params, $map );
	}
	
	/**
	 * Creates a new Subdomain.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! name				string		Name for the subdomain (the subdomain itself)
	 * ! ftpPath			string		The FTP path where the files for the subdomain will be hosted
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#subdomain-create-service-subdomaincreate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function subdomainCreate( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'name',			'type' => 'string',		'required' => true ),
			array( 'name' => 'ftpPath',			'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/subdomaincreate/', $_params, $map );
	}
	
	/**
	 * Modifies settings and parameters for an existing Subdomain.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! ftpPath			string		The FTP path where the files for the subdomain will be hosted
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#subdomain-update-service-subdomainupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function subdomainUpdate( $serviceName, $entityID, array $args = array())
	{
		$_params = array_merge(
			array(
				'serviceName' => $serviceName,
				'entityID' => $entityID
			),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true ),
			array( 'name' => 'ftpPath',			'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/subdomainupdate/', $_params, $map );
	}
	
	/**
	 * Deletes a Subdomain.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#subdomain-delete-service-subdomaindelete
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function subdomainDelete( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/subdomaindelete/', $_params, $map );
	}
	
	/**
	 * Searchs/Lists redirections in an specific service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - filter			string		Filter results by this text
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#redirect-list-service-redirectlist
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function redirectList( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'pageLength',		'type' => 'integer',	'required' => false ),
			array( 'name' => 'page',			'type' => 'integer',	'required' => false,	'min' => 1 ),
			array( 'name' => 'filter',			'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/redirectlist/', $_params, $map );
	}
	
	/**
	 * Retrieves information from an existing redirection.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#redirect-get-info-service-redirectgetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function redirectGetInfo( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/redirectgetinfo/', $_params, $map );
	}
	
	/**
	 * Creates a new Redirection.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! origin			string		Origin of the Redirection (from)
	 * ! destination	string		Destination of the Redirection (to)
	 * ! type			string		One of: 301, 302, frame
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#redirect-create-service-redirectcreate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function redirectCreate( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'origin',			'type' => 'string',		'required' => true ),
			array( 'name' => 'destination',		'type' => 'string',		'required' => true ),
			array( 'name' => 'type',			'type' => 'list',		'required' => true,		'list' => array( '301', '302', 'frame' ))
		);
		
		return $this->execute( 'service/redirectcreate/', $_params, $map );
	}
	
	/**
	 * Modifies settings and parameters for an existing Redirection.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! destination	string		Destination of the Redirection (to)
	 * ! type			string		One of: 301, 302, frame
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#redirect-update-service-redirectupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function redirectUpdate( $serviceName, $entityID, array $args = array())
	{
		$_params = array_merge(
			array(
				'serviceName' => $serviceName,
				'entityID' => $entityID
			),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true ),
			array( 'name' => 'destination',		'type' => 'string',		'required' => true ),
			array( 'name' => 'type',			'type' => 'list',		'required' => true,		'list' => array( '301', '302', 'frame' ))
		);
		
		return $this->execute( 'service/redirectupdate/', $_params, $map );
	}
	
	/**
	 * Deletes a Redirection.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#redirect-delete-service-redirectdelete
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function redirectDelete( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/redirectdelete/', $_params, $map );
	}
	
	/**
	 * Searchs/Lists email accounts in an specific service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - filter			string		Filter results by this text
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-list-service-maillist
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailList( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'pageLength',		'type' => 'integer',	'required' => false ),
			array( 'name' => 'page',			'type' => 'integer',	'required' => false,	'min' => 1 ),
			array( 'name' => 'filter',			'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/maillist/', $_params, $map );
	}
	
	/**
	 * Retrieves information from an existing email account.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-get-info-service-mailgetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailGetInfo( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/mailgetinfo/', $_params, $map );
	}
	
	/**
	 * Creates a new Email Account.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! name			string		Name of the account (username)
	 * ! password		string		Password for the account
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-create-service-mailcreate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailCreate( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'name',			'type' => 'string',		'required' => true ),
			array( 'name' => 'password',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/mailcreate/', $_params, $map );
	}
	
	/**
	 * Modifies settings and parameters for an existing Email Account.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! password		string		Password for the account
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-update-service-mailupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailUpdate( $serviceName, $entityID, array $args = array())
	{
		$_params = array_merge(
			array(
				'serviceName' => $serviceName,
				'entityID' => $entityID
			),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true ),
			array( 'name' => 'password',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/mailupdate/', $_params, $map );
	}
	
	/**
	 * Deletes an Email account.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-delete-service-maildelete
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailDelete( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/maildelete/', $_params, $map );
	}
	
	/**
	 * Searchs/Lists email alias in an specific service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - filter			string		Filter results by this text
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-alias-list-service-mailaliaslist
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailAliasList( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'pageLength',		'type' => 'integer',	'required' => false ),
			array( 'name' => 'page',			'type' => 'integer',	'required' => false,	'min' => 1 ),
			array( 'name' => 'filter',			'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/mailaliaslist/', $_params, $map );
	}
	
	/**
	 * Retrieves information from an existing email alias.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-alias-get-info-service-mailaliasgetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailAliasGetInfo( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/mailaliasgetinfo/', $_params, $map );
	}
	
	/**
	 * Creates a new Email Alias.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! name			string		Name of the alias (the part before the '@')
	 * ! target			string		Target Email account(s) (for more than one, use ',')
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-alias-create-service-mailaliascreate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailAliasCreate( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'name',			'type' => 'string',		'required' => true ),
			array( 'name' => 'target',			'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/mailaliascreate/', $_params, $map );
	}
	
	/**
	 * Modifies settings and parameters for an existing Email Alias.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! target			string		Target Email account(s) (for more than one, use ',')
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-alias-update-service-mailaliasupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailAliasUpdate( $serviceName, $entityID, array $args = array())
	{
		$_params = array_merge(
			array(
				'serviceName' => $serviceName,
				'entityID' => $entityID
			),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true ),
			array( 'name' => 'target',			'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/mailaliasupdate/', $_params, $map );
	}
	
	/**
	 * Deletes an Email Alias.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#mail-alias-delete-service-mailaliasdelete
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function mailAliasDelete( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/mailaliasdelete/', $_params, $map );
	}
	
	/**
	 * Searchs/Lists DNS zones in an specific service.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * - pageLength		integer		Number of results to display in a single query
	 * - page			integer		Results page
	 * - filter			string		Filter results by this text
	 * - filterType		string		Filter by type
	 * - filterValue	string		Filter by value
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-list-service-dnslist
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsList( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );

		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'pageLength',		'type' => 'integer',	'required' => false ),
			array( 'name' => 'page',			'type' => 'integer',	'required' => false,	'min' => 1 ),
			array( 'name' => 'filter',			'type' => 'string',		'required' => false ),
			array( 'name' => 'filterType',		'type' => 'list',		'required' => false,	'list' => array( 'A', 'AAAA', 'CNAME', 'MX', 'SRV', 'TXT', 'NS', 'CAA' )),
			array( 'name' => 'filterValue',		'type' => 'string',		'required' => false)
		);

		return $this->execute( 'service/dnslist/', $_params, $map );
	}
	
	/**
	 * Retrieves information from an existing DNS zone.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-get-info-service-dnsgetinfo
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsGetInfo( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/dnsgetinfo/', $_params, $map );
	}
	
	/**
	 * Creates a new DNS zone.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! name			string		Name of the DNS zone
	 * ! type			string		One of: A, AAAA, CNAME, MX, SRV, TXT, NS, CAA
	 * ! value			string		The value of the DNS zone, depending on its type
	 * - ttl			string		Time to live (use '-' for default)
	 * - priority		string		Priority of this zone (use '-' for default)
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-create-service-dnscreate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$args				Associative array of parameters (see table)
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsCreate( $serviceName, array $args = array())
	{
		$_params = array_merge( array( 'serviceName' => $serviceName ), $args );
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'name',			'type' => 'string',		'required' => true ),
			array( 'name' => 'type',			'type' => 'list',		'required' => true ,	'list' => array( 'A', 'AAAA', 'CNAME', 'MX', 'SRV', 'TXT', 'NS', 'CAA' )),
			array( 'name' => 'value',			'type' => 'string',		'required' => true ),
			array( 'name' => 'ttl',				'type' => 'string',		'required' => false ),
			array( 'name' => 'priority',		'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/dnscreate/', $_params, $map );
	}
	
	/**
	 * Modifies settings and parameters for an existing DNS zone.
	 * Accepts an associative array with the following parameters:
	 *
	 * ! = required
	 * ! value			string		The value of the DNS zone, depending on its type
	 * - ttl			string		Time to live (use '-' for default)
	 * - priority		string		Priority of this zone (use '-' for default)
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-update-service-dnsupdate
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsUpdate( $serviceName, $entityID, array $args = array())
	{
		$_params = array_merge(
			array(
				'serviceName' => $serviceName,
				'entityID' => $entityID
			),
			$args
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true ),
			array( 'name' => 'value',			'type' => 'string',		'required' => true ),
			array( 'name' => 'ttl',				'type' => 'string',		'required' => false ),
			array( 'name' => 'priority',		'type' => 'string',		'required' => false )
		);
		
		return $this->execute( 'service/dnsupdate/', $_params, $map );
	}
	
	/**
	 * Deletes a DNS zone.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-delete-service-dnsdelete
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	string		$entityID			Entity identifier
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsDelete( $serviceName, $entityID )
	{
		$_params = array(
			'serviceName' => $serviceName,
			'entityID' => $entityID
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'entityID',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/dnsdelete/', $_params, $map );
	}
	
	/**
	 * Restores the entire service to the default DNS zones.
	 * This, of course, destrois all previous DNS zones.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-restore-service-dnsrestore
	 *
	 * @param	string		$serviceName		Name of the service
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsRestore( $serviceName )
	{
		$_params = array( 'serviceName' => $serviceName );
		
		$map = array(
			array( 'name' => 'serviceName', 	'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/dnsrestore/', $_params, $map );
	}
	
	/**
	 * Sets all the DNS zones for the service directly.
	 * This destrois all previous DNS zones that may exist. Accepts an associative, multidimensional
	 * array of parameters.
	 *
	 * Each array in $dnsZoneData must include the following parameters:
	 * ! name			string		Name of the DNS zone
	 * ! type			string		One of: A, AAAA, CNAME, MX, SRV, TXT, NS, CAA
	 * ! ttl			string		Time to live (use '-' to set default)
	 * ! priority		string		Priority of the zone (use '-' to set default)
	 * ! value			string		Value for this zone, depending on its type
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-set-service-dnssetzone
	 *
	 * @param	string		$serviceName		Name of the service
	 * @param	array		$dnsZoneData		Multidimensional associative array containing DNS data
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsSetZone( $serviceName, array $dnsZoneData = array())
	{
		$_params = array_merge(
			array(
				'serviceName' => $serviceName,
				'dnsZoneData' => base64_encode( json_encode( $dnsZoneData ))
			)
		);
		
		$map = array(
			array( 'name' => 'serviceName',		'type' => 'string',		'required' => true ),
			array( 'name' => 'dnsZoneData',		'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/dnssetzone/', $_params, $map );
	}
	
	/**
	 * Deletes all DNS zones inside a service.
	 * This, of course, will destroy all DNS zones. Use with caution.
	 *
	 * @link https://dev.dondominio.com/api/docs/api/#dns-zone-delete-all-service-dnsdeleteall
	 *
	 * @param	string		$serviceName		Name of the service
	 *
	 * @return	DonDominioAPIResponse
	 */
	protected function dnsDeleteAll( $serviceName )
	{
		$_params = array( 'serviceName' => $serviceName );
		
		$map = array(
			array( 'name' => 'serviceName', 	'type' => 'string',		'required' => true )
		);
		
		return $this->execute( 'service/dnsdeleteall/', $_params, $map );
	}
}