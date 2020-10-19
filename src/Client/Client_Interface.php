<?php

/**
 * Interface for DonDominio API clients.
 * @package DonDominioPHP
 * @subpackage Clients
 */

/**
 * Interface for DonDominio API clients.
 */
interface DonDominioAPIClientInterface
{	
	/**
	 * Call an API endpoint.
	 * @param string $url URL to be requested
	 * @param array $args Arguments to be passed along the request
	 * @return Response
	 */
	public function execute($url, array $args = array());
}