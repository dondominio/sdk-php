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
     * @link https://dev.dondominio.com/api/docs/api/#ssl-product-list-ssl-productlist
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
     * @link https://dev.dondominio.com/api/docs/api/#ssl-product-get-info-ssl-productgetinfo
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
     * - sanMaxDomains      string		Max number of domains that can have the certificate
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
            ['name' => 'sanMaxDomains', 'type' => 'integer',    'required' => false],
        ];

        return $this->execute('ssl/list/', $args, $map);
    }

    /**
     * Get certificate info
     * 
     *  ! = required
     * - infoType		    string		Type of information to get. Accepted values: 'status', 'ssldata'
     * - sanMaxDomains      string		Max number of domains that can have the certificate
     *
     * @link https://dev.dondominio.com/api/docs/api/#ssl-get-info-ssl-getinfo
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
            ['name' => 'infoType',      'type' => 'list',       'required' => false, 'list' => ['status', 'ssldata', 'validationStatus']],
            ['name' => 'sanMaxDomains', 'type' => 'integer',    'required' => false],
        ];

        return $this->execute('ssl/getinfo/', $args, $map);
    }

    /**
     * Create Cerfificate
     * 
     *  ! = required
     * ! csrData		            string		CSR data (including -----BEGIN CERTIFICATE REQUEST----- and -----END CERTIFICATE REQUEST-----)
     * - keyData		            string		Private key of CSR data (including -----BEGIN PRIVATE KEY----- and -----END PRIVATE KEY-----)
     * - period		                integer		Certificate period
     * - validationMethod           integer		Certificate validation method for the domain at CommonName
     * ! adminContact[Data]         array		Administrative contact data  (ID Required)
     * - techContact[Data]          array		Technical contact data
     * - orgContact[Data]           array		Organization contact data
     * - alt_name_[Number]          string      Alternative Name of the certificate (Just for multi-domain certificates)
     * - alt_validation_[Number]    string      Validation method of the Alternative Name (Just for multi-domain certificates)
     *
     * @link https://dev.dondominio.com/api/docs/api/#ssl-create-ssl-create
     *
     * @param int $productId Certificate ID
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function create($productID, array $args = [])
    {
        $args['productID'] = $productID;

        $map = [
            ['name' => 'productID',                 'type' => 'integer',    'required' => true],
            ['name' => 'csrData',                   'type' => 'string',     'required' => true],
            ['name' => 'keyData',                   'type' => 'string',     'required' => false],
            ['name' => 'period',                    'type' => 'integer',    'required' => false],
            ['name' => 'validationMethod',          'type' => 'string',     'required' => false],

            ['name' => 'adminContactID',            'type' => 'contactID',  'required' => true],
            ['name' => 'adminContactType',          'type' => 'list',       'required' => false,    'list' => ['individual', 'organization']],
            ['name' => 'adminContactFirstName',     'type' => 'string',     'required' => false],
            ['name' => 'adminContactLastName',      'type' => 'string',     'required' => false],
            ['name' => 'adminContactOrgName',       'type' => 'string',     'required' => false],
            ['name' => 'adminContactOrgType',       'type' => 'string',     'required' => false],
            ['name' => 'adminContactIdentNumber',   'type' => 'string',     'required' => false],
            ['name' => 'adminContactEmail',         'type' => 'email',      'required' => false],
            ['name' => 'adminContactPhone',         'type' => 'phone',      'required' => false],
            ['name' => 'adminContactFax',           'type' => 'phone',      'required' => false],
            ['name' => 'adminContactAddress',       'type' => 'string',     'required' => false],
            ['name' => 'adminContactPostalCode',    'type' => 'string',     'required' => false],
            ['name' => 'adminContactCity',          'type' => 'string',     'required' => false],
            ['name' => 'adminContactState',         'type' => 'string',     'required' => false],
            ['name' => 'adminContactCountry',       'type' => 'string',     'required' => false],

            ['name' => 'techContactID',             'type' => 'contactID',  'required' => false],
            ['name' => 'techContactType',           'type' => 'list',       'required' => false,    'list' => ['individual', 'organization']],
            ['name' => 'techContactFirstName',      'type' => 'string',     'required' => false],
            ['name' => 'techContactLastName',       'type' => 'string',     'required' => false],
            ['name' => 'techContactOrgName',        'type' => 'string',     'required' => false],
            ['name' => 'techContactOrgType',        'type' => 'string',     'required' => false],
            ['name' => 'techContactIdentNumber',    'type' => 'string',     'required' => false],
            ['name' => 'techContactEmail',          'type' => 'email',      'required' => false],
            ['name' => 'techContactPhone',          'type' => 'phone',      'required' => false],
            ['name' => 'techContactFax',            'type' => 'phone',      'required' => false],
            ['name' => 'techContactAddress',        'type' => 'string',     'required' => false],
            ['name' => 'techContactPostalCode',     'type' => 'string',     'required' => false],
            ['name' => 'techContactCity',           'type' => 'string',     'required' => false],
            ['name' => 'techContactState',          'type' => 'string',     'required' => false],
            ['name' => 'techContactCountry',        'type' => 'string',     'required' => false],

            ['name' => 'orgContactID',              'type' => 'contactID',  'required' => false],
            ['name' => 'orgContactType',            'type' => 'list',       'required' => false,    'list' => ['individual', 'organization']],
            ['name' => 'orgContactFirstName',       'type' => 'string',     'required' => false],
            ['name' => 'orgContactLastName',        'type' => 'string',     'required' => false],
            ['name' => 'orgContactOrgName',         'type' => 'string',     'required' => false],
            ['name' => 'orgContactOrgType',         'type' => 'string',     'required' => false],
            ['name' => 'orgContactIdentNumber',     'type' => 'string',     'required' => false],
            ['name' => 'orgContactEmail',           'type' => 'email',      'required' => false],
            ['name' => 'orgContactPhone',           'type' => 'phone',      'required' => false],
            ['name' => 'orgContactFax',             'type' => 'phone',      'required' => false],
            ['name' => 'orgContactAddress',         'type' => 'string',     'required' => false],
            ['name' => 'orgContactPostalCode',      'type' => 'string',     'required' => false],
            ['name' => 'orgContactCity',            'type' => 'string',     'required' => false],
            ['name' => 'orgContactState',           'type' => 'string',     'required' => false],
            ['name' => 'orgContactCountry',         'type' => 'string',     'required' => false],
        ];

        return $this->execute('ssl/create/', $this->flattenContacts($args), $map);
    }

    /**
     * List all the validation email for a Certificate and his alternative methods
     * 
     *  ! = required
     * - includeAlternativeMethods  string  The response includes alternative validation methods to emails
     *
     * @link https://dev.dondominio.com/api/docs/api/#ssl-get-validation-emails-ssl-getvalidationemails
     *
     * @param string    $productId CommonName of the Certificate
     * @param array     $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function getValidationEmails($commonName, array $args = [])
    {
        $args['commonName'] = $commonName;

        $map = [
            ['name' => 'commonName',                'type' => 'string',  'required' => true],
            ['name' => 'includeAlternativeMethods', 'type' => 'bool',   'required' => false],
        ];

        return $this->execute('ssl/getvalidationemails/', $args, $map);
    }

    /**
     * Change the validation method of a commonName of a certificate that is in process or in reissue
     * 
     *  ! = required
     * ! certificateID              integer     Certificate ID
     * ! commonName                 string      Certificate commonName
     * ! includeAlternativeMethods  string      Alternative validation methods to emails
     *
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function changeValidationMethod(array $args = [])
    {

        $map = [
            ['name' => 'certificateID',             'type' => 'integer',    'required' => true],
            ['name' => 'commonName',                'type' => 'string',     'required' => true],
            ['name' => 'includeAlternativeMethods', 'type' => 'string',     'required' => true],
        ];

        return $this->execute('ssl/changevalidationmethod/', $args, $map);
    }
}