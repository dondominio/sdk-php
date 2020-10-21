<?php

/**
 * Interface for DonDominio API clients.
 * @package DonDominioPHP
 * @subpackage Client
 */

namespace Dondominio\API\Client;

interface Client_Interface
{	
    /**
     * Call an API endpoint.
     * @param string $url URL to be requested
     * @param array $args Arguments to be passed along the request
     * @return \Dondominio\API\Response\Response
     */
    public function execute($url, array $args = []);
}