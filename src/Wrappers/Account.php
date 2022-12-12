<?php

/**
 * Wrapper for the DonDominio Account API module.
 * Please read the online documentation for more information before using the module.
 * 
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

namespace Dondominio\API\Wrappers;

class Account extends \Dondominio\API\Wrappers\AbstractWrapper
{
    /**
     * Get the account information.
     *
     * @link https://dondominio.dev/es/api/docs/api/#info-account-info
     *
     * @return \Dondominio\API\Response\Response
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
     * @link https://dondominio.dev/es/api/docs/api/#zones-account-zones
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function zones(array $args = [])
    {
        $map = [
            ['name' => 'pageLength',    'type' => 'integer',    'required' => false],
            ['name' => 'page',          'type' => 'integer',    'required' => false],
            ['name' => 'tld',           'type' => 'string',     'required' => false],
            ['name' => 'tldtop',        'type' => 'string',     'required' => false]
        ];

        return $this->execute('account/zones', $args, $map);
    }

    /**
     * Gets active promotions
     *
     * @link https://dondominio.dev/es/api/docs/api/
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function promos()
    {
        return $this->execute('account/promos');
    }
}