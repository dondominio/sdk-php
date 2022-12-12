<?php

/**
 * Wrapper for the DonDominio Contact API module.
 * Please read the online documentation for more information before using the module.
 *
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

namespace Dondominio\API\Wrappers;

class Contact extends \Dondominio\API\Wrappers\AbstractWrapper
{	
    /**
     * Rewriting the proxy method for specific needs.
     *
     * @param string $method Method name
     * @param array $args Array of arguments passed to the method
     *
     * @return \Dondominio\API\Response\Response
     */
    public function proxy($method, array $args = [])
    {
        if ($method == 'list') {
            $method = 'getList';
        }

        if (!method_exists($this, $method)) {
            trigger_error('Method ' . $method . ' not found', E_USER_ERROR);
        }

        return call_user_func_array([$this, $method], $args);
    }

    /**
     * List the contacts in the account, filtered by various parameters.
     * Accepts an associative array with the following parameters:
     *
     * ! = required
     * - pageLength		integer		Max results (defaults to 1000)
     * - page			integer		Number of the page to get (defaults to 1)
     * - name			string		Filter contacts by name
     * - email			string		Filter contacts by email
     * - country		string		Filter contacts by country code
     * - identNumber	string		Filter contacts by ID number
     *
     * @link https://dondominio.dev/es/api/docs/api/#list-contact-list
     *
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function getList(array $args = [])
    {
        $map = [
            ['name' => 'pageLength',            'type' => 'integer',        'required' => false],
            ['name' => 'page',                  'type' => 'integer',        'required' => false],
            ['name' => 'name',                  'type' => 'string',         'required' => false],
            ['name' => 'email',                 'type' => 'email',          'required' => false],
            ['name' => 'country',               'type' => 'countryCode',    'required' => false],
            ['name' => 'identNumber',           'type' => 'string',         'required' => false],
            ['name' => 'verificationstatus',    'type' => 'list',           'required' => false,    'list' => ['verified', 'notapplicable', 'inprocess', 'failed']],
            ['name' => 'daaccepted',            'type' => 'boolean',        'required' => false]
        ];

        return $this->execute('contact/list/', $args, $map);
    }

    /**
     * Get all available information for a contact.
     *
     * @link https://dondominio.dev/es/api/docs/api/#get-info-contact-getinfo
     *
     * @param string $contactID Contact's ID code
     * @param string $infoType Type of information to get.
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function getInfo($contactID, $infoType = 'data')
    {
        $_params = ['contactID' => $contactID, 'infoType' => $infoType];

        $map = [
            ['name' => 'contactID', 'type' => 'contactID',  'required' => true],
            ['name' => 'infoType',  'type' => 'list',       'required' => false,    'list' => ['data']]
        ];

        return $this->execute('contact/getinfo/', $_params, $map);
    }

    /**
     * Resend the verification email for contact changes.
     *
     * @link https://dondominio.dev/es/api/docs/api/#resend-verification-mail-contact-resendverificationmail
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function resendVerificationMail($contactId)
    {
        $_params = ['contactID' => $contactId];

        $map = [
            ['name' => 'contactID', 'type' => 'contactID',  'required' => true]
        ];

        return $this->execute('contact/resendverificationmail/', $_params, $map);
    }

    /**
     * Create Contact.
     *
     * ! = required
     * ! Type		    string		Contact type
     * ! FirstName		string		First name
     * ! LastName   	string		Last name
     * ! IdentNumber    string		Tax identification number, VAT Number, ID Card number...
     * ! Email  		string		Email
     * ! Phone  		string		Phone number in +DD.DDDDDDDD format
     * ! Address    	string		Address
     * ! PostalCode 	string		Postal code
     * ! City   		string		City
     * ! State  		string		State/Province
     * ! Country    	string		Country code (https://dondominio.dev/es/api/docs/country-codes/)
     * - OrgName    	string		Organization or company name
     * - OrgType    	string		Spanish organization type (https://dondominio.dev/es/api/docs/esjuridic/)
     * - Fax        	string		Fax number in +DD.DDDDDDDDD format
     * 
     * @link https://dondominio.dev/es/api/docs/api/#create-contact-create
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function create(array $args)
    {
        $map = [
            ['name' => 'Type',          'type' => 'list',    'required' => true, 'list' => ['individual', 'organization']],
            ['name' => 'FirstName',     'type' => 'string',  'required' => true],
            ['name' => 'LastName',      'type' => 'string',  'required' => true],
            ['name' => 'IdentNumber',   'type' => 'string',  'required' => true],
            ['name' => 'Email',         'type' => 'string',  'required' => true],
            ['name' => 'Phone',         'type' => 'phone',   'required' => true],
            ['name' => 'Address',       'type' => 'string',  'required' => true],
            ['name' => 'PostalCode',    'type' => 'string',  'required' => true],
            ['name' => 'City',          'type' => 'string',  'required' => true],
            ['name' => 'State',         'type' => 'string',  'required' => true],
            ['name' => 'Country',       'type' => 'string',  'required' => true],
            ['name' => 'OrgName',       'type' => 'string',  'required' => false],
            ['name' => 'OrgType',       'type' => 'string',  'required' => false],
            ['name' => 'Fax',           'type' => 'phone',   'required' => false],
        ];

        return $this->execute('contact/create/', $args, $map);
    }
}
