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
     * @link https://dev.dondominio.com/api/docs/api/#csr-decode-ssl-csrdecode
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
     * @link https://dev.dondominio.com/api/docs/api/#csr-create-ssl-csrcreate
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

    /**
     * Product list
     *
     * ! = required
     * - pageLength		    integer		Max results (defaults to 1000)
     * - page			    integer		Number of the page to get (defaults to 1)
     * - wildcard		    bool 		Allow wildcard
     * - multidomain	    bool 		Allow multidomains
     * - validationType	    string		Validation Type. Accepted values: dv => Domain Validation, ov = > Org. Validation, ev => Ext. Validation
     * - trial			    bool		Is trial product
     * 
     * @link https://dev.dondominio.com/api/docs/api/#product-list-ssl-productlist
     *
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function productList(array $args = [])
    {
        $map = [
            ['name' => 'pageLength',        'type' => 'integer',    'required' => false],
            ['name' => 'page',              'type' => 'integer',    'required' => false],
            ['name' => 'wildcard',          'type' => 'bool',       'required' => false],
            ['name' => 'multidomain',       'type' => 'bool',       'required' => false],
            ['name' => 'validationType',    'type' => 'list',       'required' => false,    'list' => ['dv', 'ov', 'ev']],
            ['name' => 'trial',             'type' => 'bool',       'required' => false],
        ];

        return $this->execute('ssl/productlist/', $args, $map);
    }

    /**
     * Get product info
     *
     * @link https://dev.dondominio.com/api/docs/api/#product-information-ssl-productgetinfo
     *
     * @param int $productId Product ID
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function productGetInfo($productID)
    {
        $_params = ['productID' => $productID];

        $map = [
            ['name' => 'productID', 'type' => 'integer', 'required' => true]
        ];

        return $this->execute('ssl/productgetinfo/', $_params, $map);
    }

    /**
     * List of purchased certificates
     *
     * ! = required
     * - pageLength		    integer		Max results (defaults to 1000)
     * - page			    integer		Number of the page to get (defaults to 1)
     * - productID			integer		Certificate ID
     * - status			    string		Certificate status. Accepted values: 'process', 'valid', 'expired', 'renew', 'reissue', 'cancel'
     * - renewable			bool		If the certificate is renewable
     * - commonName			bool		Certificate commonName
     * 
     * @link https://dev.dondominio.com/api/docs/api/#ssl-list-ssl-list
     *
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function list(array $args = [])
    {
        $map = [
            ['name' => 'pageLength',    'type' => 'integer',    'required' => false],
            ['name' => 'page',          'type' => 'integer',    'required' => false],
            ['name' => 'productID',     'type' => 'integer',    'required' => false],
            ['name' => 'status',        'type' => 'list',       'required' => false,    'list' => ['process', 'valid', 'expired', 'renew', 'reissue', 'cancel']],
            ['name' => 'renewable',     'type' => 'bool',       'required' => false],
            ['name' => 'commonName',    'type' => 'string',     'required' => false],
        ];

        return $this->execute('ssl/list/', $args, $map);
    }

    /**
     * Get certificate info
     * 
     *  ! = required
     * - infoType		    string		Type of information to get. Accepted values: 'status', 'ssldata'
     *
     * @link https://dev.dondominio.com/api/docs/api/#certificate-information-ssl-getinfo
     *
     * @param int $productId Certificate ID
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function getInfo($certificateID, array $args = [])
    {
        $args['certificateID'] = $certificateID;

        $map = [
            ['name' => 'certificateID', 'type' => 'integer',    'required' => true],
            ['name' => 'infoType',      'type' => 'list',       'required' => false, 'list' => ['status', 'ssldata']],
        ];

        return $this->execute('ssl/getinfo/', $args, $map);
    }

    /**
     * Create Cerfificate
     * 
     *  ! = required
     * ! csrData		    string		CSR data (including -----BEGIN CERTIFICATE REQUEST----- and -----END CERTIFICATE REQUEST-----)
     * - keyData		    string		Private key of CSR data (including -----BEGIN PRIVATE KEY----- and -----END PRIVATE KEY-----)
     * - period		        integer		Certificate period
     *
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param int $productId Certificate ID
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function creat($productID, array $args = [])
    {
        $args['productID'] = $productID;

        $map = [
            ['name' => 'productID', 'type' => 'integer',  'required' => true],
            ['name' => 'csrData',   'type' => 'string',   'required' => true],
            ['name' => 'keyData',   'type' => 'string',   'required' => false],
            ['name' => 'period',    'type' => 'integer',  'required' => false],
        ];

        return $this->execute('ssl/creat/', $args, $map);
    }
}