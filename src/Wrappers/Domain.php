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
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

namespace Dondominio\API\Wrappers;

class Domain extends \Dondominio\API\Wrappers\AbstractWrapper
{
    /**
     * Rewriting the proxy method for specific needs.
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
     * Check the availibility of a domain name.
     *
     * @link https://dev.dondominio.com/api/docs/api/#check-domain-check
     *
     * @param string $domain Domain name to check
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function check($domain)
    {
        $_params = ['domain' => $domain];

        $map = [
            ['name' => 'domain', 'type' => 'domain', 'required' => true]
        ];

        return $this->execute('domain/check/', $_params, $map);
    }

    /**
     * Check if a domain can be transfered.
     *
     * @link https://dev.dondominio.com/api/docs/api/#check-for-transfer-domain-checkfortransfer
     *
     * @param string $domain Domain name to check
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function checkForTransfer($domain)
    {
        $_params = ['domain' => $domain];

        $map = [
            ['name' => 'domain', 'type' => 'domain', 'required' => true]
        ];

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
        * @link https://dev.dondominio.com/api/docs/api/#create-domain-create
        *
        * @param string $domain Domain name to register
        * @param array $args Associative array of parameters
        *
        * @return \Dondominio\API\Response\Response
        */
    protected function create($domain, array $args = [])
    {
        $_params = array_merge([
            'domain' => $domain
        ], $this->flattenContacts($args));

        $map = [
            ['name' => 'domain',        'type' => 'domain',     'required' => true],
            ['name' => 'period',        'type' => 'integer',    'required' => false],
            ['name' => 'premium',       'type' => 'boolean',    'required' => false],
            ['name' => 'nameservers',   'type' => 'string',     'required' => false],

            ['name' => 'ownerContactID',            'type' => 'contactID',  'required' => false],
            ['name' => 'ownerContactType',          'type' => 'list',       'required' => false,    'list' => ['individual', 'organization']],
            ['name' => 'ownerContactFirstName',     'type' => 'string',     'required' => false],
            ['name' => 'ownerContactLastName',      'type' => 'string',     'required' => false],
            ['name' => 'ownerContactOrgName',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactOrgType',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactIdentNumber',   'type' => 'string',     'required' => false],
            ['name' => 'ownerContactEmail',         'type' => 'email',      'required' => false],
            ['name' => 'ownerContactPhone',         'type' => 'phone',      'required' => false],
            ['name' => 'ownerContactFax',           'type' => 'phone',      'required' => false],
            ['name' => 'ownerContactAddress',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactPostalCode',    'type' => 'string',     'required' => false],
            ['name' => 'ownerContactCity',          'type' => 'string',     'required' => false],
            ['name' => 'ownerContactState',         'type' => 'string',     'required' => false],
            ['name' => 'ownerContactCountry',       'type' => 'string',     'required' => false],

            ['name' => 'adminContactID',            'type' => 'contactID',  'required' => false],
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

            ['name' => 'billingContactID',          'type' => 'contactID',  'required' => false],
            ['name' => 'billingContactType',        'type' => 'list',       'required' => false,    'list' => ['individual', 'organization']],
            ['name' => 'billingContactFirstName',   'type' => 'string',     'required' => false],
            ['name' => 'billingContactLastName',    'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgName',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgType',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactIdentNumber', 'type' => 'string',     'required' => false],
            ['name' => 'billingContactEmail',       'type' => 'email',      'required' => false],
            ['name' => 'billingContactPhone',       'type' => 'phone',      'required' => false],
            ['name' => 'billingContactFax',         'type' => 'phone',      'required' => false],
            ['name' => 'billingContactAddress',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactPostalCode',  'type' => 'string',     'required' => false],
            ['name' => 'billingContactCity',        'type' => 'string',     'required' => false],
            ['name' => 'billingContactState',       'type' => 'string',     'required' => false],
            ['name' => 'billingContactCountry',     'type' => 'string',     'required' => false]
        ];

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
        * @link https://dev.dondominio.com/api/docs/api/#transfer-domain-transfer
        *
        * @param string $domain Domain name to transfer
        * @param array $args Associative array of parameters
        *
        * @return \Dondominio\API\Response\Response
        */
    protected function transfer($domain, array $args = [])
    {
        $_params = array_merge([
            'domain' => $domain
        ], $this->flattenContacts($args));

        $map = [
            ['name' => 'domain',                    'type' => 'domain',     'required' => true],
            ['name' => 'nameservers',               'type' => 'string',     'required' => false],
            ['name' => 'authcode',                  'type' => 'string',     'required' => false],
            ['name' => 'foacontact',                'type' => 'string',     'required' => false,    'list' => ['owner', 'admin']],

            ['name' => 'ownerContactID',            'type' => 'contactID',  'required' => true,     'bypass' => 'ownerContactType'],
            ['name' => 'ownerContactType',          'type' => 'list',       'required' => true,     'bypass' => 'ownerContactID',       'list' => ['individual', 'organization']],
            ['name' => 'ownerContactFirstName',     'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactLastName',      'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactOrgName',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactOrgType',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactIdentNumber', 	'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactEmail',         'type' => 'email',      'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactPhone',         'type' => 'phone',      'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactFax',           'type' => 'phone',      'required' => false],
            ['name' => 'ownerContactAddress',       'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactPostalCode',    'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactCity',          'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactState',         'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactCountry',       'type' => 'string',     'required' => true,     'bypass' => 'ownerContactID'],

            ['name' => 'adminContactID',            'type' => 'contactID',  'required'=>false],
            ['name' => 'adminContactType',          'type' => 'list',       'required'=>false,      'list' => ['individual', 'organization']],
            ['name' => 'adminContactFirstName',     'type' => 'string',     'required'=>false],
            ['name' => 'adminContactLastName',      'type' => 'string',     'required'=>false],
            ['name' => 'adminContactOrgName',       'type' => 'string',     'required'=>false],
            ['name' => 'adminContactOrgType',       'type' => 'string',     'required'=>false],
            ['name' => 'adminContactIdentNumber',   'type' => 'string',     'required'=>false],
            ['name' => 'adminContactEmail',         'type' => 'email',      'required'=>false],
            ['name' => 'adminContactPhone',         'type' => 'phone',      'required'=>false],
            ['name' => 'adminContactFax',           'type' => 'phone',      'required'=>false],
            ['name' => 'adminContactAddress',       'type' => 'string',     'required'=>false],
            ['name' => 'adminContactPostalCode',    'type' => 'string',     'required'=>false],
            ['name' => 'adminContactCity',          'type' => 'string',     'required'=>false],
            ['name' => 'adminContactState',         'type' => 'string',     'required'=>false],
            ['name' => 'adminContactCountry',       'type' => 'string',     'required'=>false],

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

            ['name' => 'billingContactID',          'type' => 'contactID',  'required' => false],
            ['name' => 'billingContactType',        'type' => 'list',       'required' => false,    'list' => ['individual', 'organization']],
            ['name' => 'billingContactFirstName',   'type' => 'string',     'required' => false],
            ['name' => 'billingContactLastName',    'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgName',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgType',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactIdentNumber', 'type' => 'string',     'required' => false],
            ['name' => 'billingContactEmail',       'type' => 'email',      'required' => false],
            ['name' => 'billingContactPhone',       'type' => 'phone',      'required' => false],
            ['name' => 'billingContactFax',         'type' => 'phone',      'required' => false],
            ['name' => 'billingContactAddress',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactPostalCode',  'type' => 'string',     'required' => false],
            ['name' => 'billingContactCity',        'type' => 'string',     'required' => false],
            ['name' => 'billingContactState',       'type' => 'string',     'required' => false],
            ['name' => 'billingContactCountry',     'type' => 'string',     'required' => false]
        ];

        return $this->execute('domain/transfer/', $_params, $map);
    }

    /**
     * Restart a transfer already initiated.
     * Accepts an associative array with the following parameters:
     *
     * ! = required
     * - authcode		string		A new authcode to replace the old one
     *
     * @link https://dev.dondominio.com/api/docs/api/#transfer-restart-domain-transferrestart
     *
     * @param string $domain Domain name to update
     * @param string $updateType Type of information to modify (contact, nameservers, transferBlock, block, whoisPrivacy)
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function transferRestart($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',        'type' => 'domain', 'required' => true,     'bypass' => 'domainID'],
            ['name' => 'domainID',      'type' => 'string', 'required' => true,     'bypass' => 'domain'],
            ['name' => 'authcode',      'type' => 'string', 'required' => false],
            ['name' => 'foacontact',    'type' => 'string', 'required' => false,    'list' => ['owner', 'admin']],
        ];

        return $this->execute('domain/transferrestart/', $_params, $map);
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
        * @link https://dev.dondominio.com/api/docs/api/#update-domain-update
        *
        * @param string $domain Domain name to update
        * @param string $updateType Type of information to modify (contact, nameservers, transferBlock, block, whoisPrivacy)
        * @param array $args Associative array of parameters
        *
        * @return \Dondominio\API\Response\Response
        */
    protected function update($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $this->flattenContacts($args));

        $map = [
            ['name' => 'domain',                    'type' => 'domain',     'required' => true,     'bypass' => 'domainID'],
            ['name' => 'domainID',                  'type' => 'string',     'required' => true,     'bypass' => 'domain'],
            ['name' => 'updateType',                'type' => 'list',       'required' => true,     'list' => ['contact', 'nameservers', 'transferBlock', 'block', 'whoisPrivacy', 'renewalMode', 'tag', 'viewWhois']],

            ['name' => 'ownerContactID',            'type' => 'contactID',  'required' => false,    'bypass' => 'ownerContactType'],
            ['name' => 'ownerContactType',          'type' => 'list',       'required' => false,    'bypass' => 'ownerContactID',   'list' => ['individual', 'organization']],
            ['name' => 'ownerContactFirstName',     'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactLastName',      'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactOrgName',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactOrgType',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactIdentNumber',   'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactEmail',         'type' => 'email',      'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactPhone',         'type' => 'phone',      'required' => false ,   'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactFax',           'type' => 'phone',      'required' => false],
            ['name' => 'ownerContactAddress',       'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactPostalCode',    'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactCity',          'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactState',         'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],
            ['name' => 'ownerContactCountry',       'type' => 'string',     'required' => false,    'bypass' => 'ownerContactID'],

            ['name' => 'adminContactID',            'type' => 'contactID',  'required' => false],
            ['name' => 'adminContactType',          'type' => 'list',       'required' => false, 	'list' => ['individual', 'organization']],
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

            ['name' => 'billingContactID',          'type' => 'contactID',  'required' => false],
            ['name' => 'billingContactType',        'type' => 'list',       'required' => false,    'list' => ['individual', 'organization']],
            ['name' => 'billingContactFirstName',   'type' => 'string',     'required' => false],
            ['name' => 'billingContactLastName',    'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgName',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgType',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactIdentNumber', 'type' => 'string',     'required' => false],
            ['name' => 'billingContactEmail',       'type' => 'email',      'required' => false],
            ['name' => 'billingContactPhone',       'type' => 'phone',      'required' => false],
            ['name' => 'billingContactFax',         'type' => 'phone',      'required' => false],
            ['name' => 'billingContactAddress',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactPostalCode',  'type' => 'string',     'required' => false],
            ['name' => 'billingContactCity',        'type' => 'string',     'required' => false],
            ['name' => 'billingContactState',       'type' => 'string',     'required' => false],
            ['name' => 'billingContactCountry',     'type' => 'string',     'required' => false],

            ['name' => 'nameservers',   'type' => 'string',     'required' => false],
            ['name' => 'transferBlock', 'type' => 'boolean',    'required' => false],
            ['name' => 'block',         'type' => 'boolean',    'required' => false],
            ['name' => 'whoisPrivacy',  'type' => 'boolean',    'required' => false],
            ['name' => 'viewWhois',     'type' => 'boolean',    'required' => false],
            ['name' => 'renewalMode',   'type' => 'list',       'required' => false,    'list' => ['autorenew', 'manual', 'letexpire']],
            ['name' => 'tag',           'type' => 'string',     'required' => false]
        ];

        return $this->execute('domain/update/', $_params, $map);
    }

    /**
     * Modify nameservers for a domain.
     * This method expects an array of nameservers that should look like this:
     *
     * $nameservers = array('ns1.dns.com', 'ns2.dns.com)
     *
     * @link https://dev.dondominio.com/api/docs/api/#update-nameservers-domain-updatenameservers
     *
     * @param string $domain Domain name or Domain ID to be modified
     * @param array $nameservers Array containing the nameservers
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function updateNameServers($domain, array $nameservers = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), [
            'nameservers' => implode(',', $nameservers)
        ]);

        $map = [
            ['name' => 'domain',        'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',      'type' => 'string', 'required' => true, 'bypass' => 'domain'],
            ['name' => 'nameservers',   'type' => 'string', 'required' => true]
        ];

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
     * @link https://dev.dondominio.com/api/docs/api/#update-contacts-domain-updatecontacts
     *
     * @param string $domain Domain name or Domain ID to be modified
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function updateContacts($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $this->flattenContacts($args));

        $map = [
            ['name' => 'domain',   'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID', 'type' => 'string', 'required' => true, 'bypass' => 'domain'],

            ['name' => 'ownerContactID',            'type' => 'contactID',  'required' => false,    'bypass'=>'ownerContactType'],
            ['name' => 'ownerContactType',          'type' => 'list',       'required' => false,    'bypass'=>'ownerContactID', 'list' => ['individual', 'organization']],
            ['name' => 'ownerContactFirstName',     'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactLastName',      'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactOrgName',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactOrgType',       'type' => 'string',     'required' => false],
            ['name' => 'ownerContactIdentNumber',   'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactEmail',         'type' => 'email',      'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactPhone',         'type' => 'phone',      'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactFax',           'type' => 'phone',      'required' => false],
            ['name' => 'ownerContactAddress',       'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactPostalCode',    'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactCity',          'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactState',         'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],
            ['name' => 'ownerContactCountry',       'type' => 'string',     'required' => false,    'bypass'=>'ownerContactID'],

            ['name' => 'adminContactID',            'type' => 'contactID',  'required' => false],
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

            ['name' => 'billingContactID',          'type' => 'contactID',  'required' => false],
            ['name' => 'billingContactType',        'type' => 'list',       'required' => false, 	'list' => ['individual', 'organization']],
            ['name' => 'billingContactFirstName',   'type' => 'string',     'required' => false],
            ['name' => 'billingContactLastName',    'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgName',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactOrgType',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactIdentNumber', 'type' => 'string',     'required' => false],
            ['name' => 'billingContactEmail',       'type' => 'email',      'required' => false],
            ['name' => 'billingContactPhone',       'type' => 'phone',      'required' => false],
            ['name' => 'billingContactFax',         'type' => 'phone',      'required' => false],
            ['name' => 'billingContactAddress',     'type' => 'string',     'required' => false],
            ['name' => 'billingContactPostalCode',  'type' => 'string',     'required' => false],
            ['name' => 'billingContactCity',        'type' => 'string',     'required' => false],
            ['name' => 'billingContactState',       'type' => 'string',     'required' => false],
            ['name' => 'billingContactCountry',     'type' => 'string',     'required' => false]
        ];

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
     * @link https://dev.dondominio.com/api/docs/api/#gluerecord-create-domain-gluerecordcreate
     *
     * @param string $domain Domain name or Domain ID to be modified
     * @param string $name Name of the gluerecord to be created
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function glueRecordCreate( $domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
            ['name' => 'name',      'type' => 'string', 'required' => true],
            ['name' => 'ipv4',      'type' => 'ipv4',   'required' => true],
            ['name' => 'ipv6',      'type' => 'ipv6',   'required' => false]
        ];

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
     * @link https://dev.dondominio.com/api/docs/api/#gluerecord-update-domain-gluerecordupdate
     *
     * @param string $domain Domain name or Domain ID to be modified
     * @param string $name Name of the gluerecord to be updated
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function glueRecordUpdate($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
            ['name' => 'name',      'type' => 'string', 'required' => true],
            ['name' => 'ipv4',      'type' => 'ipv4',   'required' => true],
            ['name' => 'ipv6',      'type' => 'ipv6',   'required' => false]
        ];

        return $this->execute('domain/gluerecordupdate/', $_params, $map);
    }

    /**
     * Deletes an existing gluerecord for a domain.
     *
     * @link https://dev.dondominio.com/api/docs/api/#gluerecord-delete-domain-gluerecorddelete
     *
     * @param string $domain Domain name or Domain ID to be modified
     * @param string $name Name of the gluerecord to be deleted
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function glueRecordDelete($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
            ['name' => 'name',      'type' => 'string', 'required' => true]
        ];

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
     * - infoType		string		Type of information to get. Accepted values:
     *								status, contact, nameservers, authcode, service, gluerecords.
        *
        * @link https://dev.dondominio.com/api/docs/api/#list-domain-list
        *
        * @param array $args Associative array of parameters
        *
        * @return \Dondominio\API\Response\Response
        */
    protected function getList(array $args = [])
    {
        $_params = $args;

        $map = [
            ['name' => 'pageLength',        'type' => 'integer',    'required' => false],
            ['name' => 'page',              'type' => 'integer',    'required' => false],
            ['name' => 'domain',            'type' => 'domain',     'required' => false],
            ['name' => 'word',              'type' => 'string',     'required' => false],
            ['name' => 'tld',               'type' => 'string',     'required' => false],
            ['name' => 'renewable',         'type' => 'boolean',    'required' => false],
            ['name' => 'infoType',          'type' => 'list',       'required' => false,    'list' => ['status', 'contact', 'nameservers', 'service', 'gluerecords']],
            ['name' => 'owner',             'type' => 'string',     'required' => false],
            ['name' => 'tag',               'type' => 'string',     'required' => false],
            ['name' => 'status',            'type' => 'string',     'required' => false],
            ['name' => 'ownerverification', 'type' => 'list',       'required' => false,    'list' => ['verified', 'notapplicable', 'inprocess', 'failed']]
        ];

        return $this->execute('domain/list/', $_params, $map);
    }

    /**
     * Get information from a domain in the account.
     * Accepts an associative array with the following parameters:
     *
     * ! = required
     * - infoType		string		Type of information to get. Accepted values:
     *								status, contact, nameservers, authcode, service, gluerecords, dnssec.
        *
        * @link https://dev.dondominio.com/api/docs/api/#get-info-domain-getinfo
        *
        * @param string $domain Domain name or Domain ID to get the information from
        * @param array $args Associative array of parameters
        *
        * @return \Dondominio\API\Response\Response
        */
    protected function getInfo($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
            ['name' => 'infoType',  'type' => 'list',   'required' => true, 'list' => ['status', 'contact', 'nameservers', 'authcode', 'service', 'gluerecords', 'dnssec']]
        ];

        return $this->execute('domain/getinfo/', $_params, $map);
    }

    /**
     * Get the authcode for a domain in the account.
     *
     * @link https://dev.dondominio.com/api/docs/api/#get-authcode-domain-getauthcode
     *
     * @param string $domain Domain name or Domain ID to get the authcode for
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function getAuthCode($domain)
    {
        $_params = $this->getDomainOrDomainID($domain);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
        ];

        return $this->execute('domain/getauthcode/', $_params, $map);
    }

    /**
     * Get the nameservers for a domain in the account.
     *
     * @link https://dev.dondominio.com/api/docs/api/#get-nameservers-domain-getnameservers
     *
     * @param string $domain Domain name or Domain ID to get the nameservers for
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function getNameServers($domain)
    {
        $_params = $this->getDomainOrDomainID($domain);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
        ];

        return $this->execute('domain/getnameservers/', $_params, $map);
    }

    /**
     * Get the gluerecords for a domain in the account.
     *
     * @link https://dev.dondominio.com/api/docs/api/#get-gluerecords-domain-getgluerecords
     *
     * @param string $domain Domain name or Domain ID to get the gluerecords for
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function getGlueRecords($domain)
    {
        $_params = $this->getDomainOrDomainID($domain);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
        ];

        return $this->execute('domain/getgluerecords/', $_params, $map);
    }

    /**
     * Retrieve the DNSSEC entries associated with a domain
     *
     * @link http://dev3.dondominio.com/api/docs/api/#get-dnssec-domain-getdnssec
     *
     * @param string $domain Domain name or Domain ID to get the dnssec for
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function getDnsSec($domain)
    {
        $_params = $this->getDomainOrDomainID($domain);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
        ];

        return $this->execute('domain/getdnssec/', $_params, $map);
    }

    /**
     * Creates a DNSSEC entry for the specified domain.
     * Accepts an associative array with the following parameters:
     *
     * ! = required
     * ! keytag		integer		Keytag for the DNSSEC entry
     * ! algorithm	integer		Algorithm to use for the DNSSEC entry
     * ! digesttype	integer		Type of digest to use for the DNSSEC entry
     * ! digest		string		Digest for the DNSSEC entry
     *
     * @link https://dev.dondominio.com/api/docs/api/#dnssec-create-domain-dnsseccreate
     *
     * @param string $domain Domain name or Domain ID to which attach the DNSSEC entry
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function dnsSecCreate($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',        'type' => 'domain',     'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',      'type' => 'string',     'required' => true, 'bypass' => 'domain'],
            ['name' => 'keytag',        'type' => 'integer',    'required' => true],
            ['name' => 'algorithm',     'type' => 'integer',    'required' => true],
            ['name' => 'digesttype',    'type' => 'integer',    'required' => true],
            ['name' => 'digest',        'type' => 'string',     'required' => true],
        ];

        return $this->execute('domain/dnsseccreate/', $_params, $map);
    }

    /**
     * Deletes an existing DNSSEC entry in the specified domain.
     * Accepts an associative array with the following parameters:
     *
     * ! = required
     * ! name		string		Name of the DNS server associated with the entry
     * ! keytag		integer		Keytag for the DNSSEC entry
     * ! algorithm	integer		Algorithm to use for the DNSSEC entry
     * ! digesttype	integer		Type of digest to use for the DNSSEC entry
     * ! digest		string		Digest for the DNSSEC entry
     *
     * @link https://dev.dondominio.com/api/docs/api/#dnssec-delete-domain-dnssecdelete
     *
     * @param string $domain Domain name or Domain ID containing the DNSSEC entry
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function dnsSecDelete($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',        'type' => 'domain',     'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',      'type' => 'string',     'required' => true, 'bypass' => 'domain'],
            ['name' => 'name',          'type' => 'string',     'required' => true],
            ['name' => 'keytag',        'type' => 'integer',    'required' => true],
            ['name' => 'algorithm',     'type' => 'integer',    'required' => true],
            ['name' => 'digesttype',    'type' => 'integer',    'required' => true],
            ['name' => 'digest',        'type' => 'string',     'required' => true],
        ];

        return $this->execute('domain/dnssecdelete/', $_params, $map);
    }

    /**
     * Attempts to renew a domain in the account.
     * Accepts an associative array with the following parameters:
     *
     * ! = required
     * - period		integer		Number of years to renew the domain for
     *
     * @link https://dev.dondominio.com/api/docs/api/#renew-domain-renew
     *
     * @param string $domain Domain name or Domain ID to renew
     * @param string $curExpDate Current expiration date for this domain
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function renew($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',    'type' => 'domain',     'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string',     'required' => true, 'bypass' => 'domain'],
            ['name'=>'curExpDate',  'type' => 'date',       'required' => true],
            ['name'=>'period',      'type' => 'integer',    'required' => false]
        ];

        return $this->execute('domain/renew/', $_params, $map);
    }

    /**
     * Performs a whois lookup for a domain name.
     * Returns whois data for a domain in a single string field. By default,
     * only domains on the user account can be queried.
     *
     * @link https://dev.dondominio.com/api/docs/api/#whois-domain-whois
     *
     * @param string $domain Domain name to be queried
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function whois($domain)
    {
        $_params = ['domain' => $domain];

        $map = [
            ['name' => 'domain', 'type' => 'domain', 'required' => true]
        ];

        return $this->execute('domain/whois/', $_params, $map);
    }

    /**
     * Resends the contact data verification email.
     *
     * @link https://dev.dondominio.com/api/docs/api/#resend-verification-mail-domain-resendverificationmail
     *
     * @param string $domain Domain or Domain ID to send the verification email for
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function resendVerificationMail($domain)
    {
        $_params = $this->getDomainOrDomainID($domain);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
        ];

        return $this->execute('domain/resendverificationmail/', $_params, $map);
    }

    /**
     * Resends the FOA authorization email to the owner contact of a domain.
     *
     * @link https://dev.dondominio.com/api/docs/api/#resend-foa-mail-domain-resendfoamail
     *
     * @param string $domain Domain or Domain ID to send the verification mail for
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function resendFOAMail($domain)
    {
        $_params = $this->getDomainOrDomainID($domain);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
        ];

        return $this->execute('domain/resendfoamail/', $_params, $map);
    }

    /**
     * Resets the domain authorization process (only for domains with transfer in process)
     *
     * @link https://dev.dondominio.com/api/docs/api/#reset-foa-domain-resetfoa
     *
     * @param string $domain Domain or Domain ID to send the verification mail for
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function resetFOA($domain)
    {
        $_params = $this->getDomainOrDomainID($domain);

        $map = [
            ['name' => 'domain',    'type' => 'domain', 'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',  'type' => 'string', 'required' => true, 'bypass' => 'domain'],
        ];

        return $this->execute('domain/resetfoa/', $_params, $map);
    }

    /**
     * Gets the history for a specific domain
     *
     * ! = required
     * - pageLength		integer		Max results (defaults to 1000)
     * - page			integer		Number of the page to get (defaults to 1)
     *
     * @link https://dev.dondominio.com/api/docs/api/#get-history-domain-gethistory
     *
     * @param string $domain Domain name or Domain ID
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function getHistory($domain, array $args = [])
    {
        $_params = array_merge($this->getDomainOrDomainID($domain), $args);

        $map = [
            ['name' => 'domain',        'type' => 'domain',     'required' => true, 'bypass' => 'domainID'],
            ['name' => 'domainID',      'type' => 'string',     'required' => true, 'bypass' => 'domain'],
            ['name' => 'pageLength',    'type' => 'integer',    'required' => false],
            ['name' => 'page',          'type' => 'integer',    'required' => false]
        ];

        return $this->execute('domain/gethistory/', $_params, $map);
    }

    /**
     * Gets deleted domains list
     *
     * ! = required
     * - pageLength		integer		Max results (defaults to 1000)
     * - page			integer		Number of the page to get (defaults to 1)
     *
     * @link https://dev.dondominio.com/api/docs/api/#list-deleted-domain-listdeleted
     *
     * @param array $args Associative array of parameters
     *
     * @return \Dondominio\API\Response\Response
     */
    protected function listDeleted(array $args = [])
    {
        $_params = $args;

        $map = [
            ['name' => 'pageLength',    'type' => 'integer',    'required' => false],
            ['name' => 'page',          'type' => 'integer',    'required' => false]
        ];

        return $this->execute('domain/listdeleted/', $_params, $map);
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
        if (strpos($domain, '.')) {
            return ['domain' => $domain];
        }

        return ['domainID' => $domain];
    }
}