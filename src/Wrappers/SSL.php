<?php

/**
 * Wrapper for the DonDominio SSL API module.
 * Please read the online documentation for more information before using the module.
 * 
 * @package DonDominioPHP
 * @subpackage Wrappers
 */
 
namespace Dondominio\API\Wrappers;

class SSL extends \Dondominio\API\Wrappers\AbstractWrapper
{

    /**
     * Decode the parameters contained in a CSR.
     *
     * @link https://dev.dondominio.com/api/docs/api/#ssl-csrdecode
     * @link http://en.wikipedia.org/wiki/Certificate_signing_request
     *
     * @param string $csrData CSR data (including ---BEGIN--- and ---END---)
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function csrDecode($csrData)
    {
        if (empty($csrData)) {
            $csrData = '';
        }

        $_params = ['csrData' => $csrData];

        $map = [
            ['name' => 'csrData', 'type' => 'string', 'required' => true]
        ];

        return $this->execute('ssl/csrdecode/', $_params, $map);
    }

    /**
     * Create CSR
     *
     * ! = required
     * ! commonName	            string  Domain or subdomain
     * ! organizationName		string  Enterprise or Organizacion name
     * ! organizationalUnitName string  The section/division of the company that will manage the certificate.
     * ! countryName		    string  Enterprise Country
     * ! stateOrProvinceName	string  The province where your company is located.
     * ! localityName		    string  The town where your company is located.
     * ! emailAddress		    string  The email account used to contact your company.
     * 
     * @link https://dev.dondominio.com/api/docs/api/#ssl-csrcreate
     * @link http://en.wikipedia.org/wiki/Certificate_signing_request
     *
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function csrCreate(array $args)
    {
        $map = [
            ['name' => 'commonName',                'type' => 'string', 'required' => true],
            ['name' => 'organizationName',          'type' => 'string', 'required' => true],
            ['name' => 'organizationalUnitName',    'type' => 'string', 'required' => true],
            ['name' => 'countryName',               'type' => 'string', 'required' => true],
            ['name' => 'stateOrProvinceName',       'type' => 'string', 'required' => true],
            ['name' => 'localityName',              'type' => 'string', 'required' => true],
            ['name' => 'emailAddress',              'type' => 'string', 'required' => true],
        ];

        return $this->execute('ssl/csrcreate/', $args, $map);
    }
}