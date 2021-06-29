<?php

/**
 * Wrapper for the DonDominio SSL API module.
 * Please read the online documentation for more information before using the module.
 * 
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

namespace Dondominio\API\Wrappers;

class User extends \Dondominio\API\Wrappers\AbstractWrapper
{
    /**
     * User list
     *
     * ! = required
     * - pageLength		    integer		Max results (defaults to 1000)
     * - page			    integer		Number of the page to get (defaults to 1)
     * - status			    string		User Status (enabled, disabled)
     * - username		    string		User Username
     * - domainName		    string		Domain assigned to the user
     * 
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function list(array $args = [])
    {
        $map = [
            ['name' => 'pageLength',        'type' => 'integer',    'required' => false],
            ['name' => 'page',              'type' => 'integer',    'required' => false],
            ['name' => 'status',            'type' => 'list',       'required' => false,    'list' => ['enabled', 'disabled']],
            ['name' => 'username',          'type' => 'string',     'required' => false],
            ['name' => 'domainName',        'type' => 'string',     'required' => false],
        ];

        return $this->execute('user/list/', $args, $map);
    }

    /**
     * User info
     *
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param string $username User Username
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function getInfo(string $username)
    {
        $args = [
            'username' => $username
        ];

        $map = [
            ['name' => 'username', 'type' => 'string', 'required' => true],
        ];

        return $this->execute('user/getinfo/', $args, $map);
    }

    /**
     * Update user status
     *
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param string $username User Username
     * @param string $args New Status (enabled, disabled)
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function updateStatus(string $username, string $status)
    {
        $args['username'] = $username;
        $args['status'] = $status;

        $map = [
            ['name' => 'username',          'type' => 'string',     'required' => true],
            ['name' => 'status',            'type' => 'list',       'required' => true,     'list' => ['enabled', 'disabled']],
        ];

        return $this->execute('user/updatestatus/', $args, $map);
    }

    /**
     * Update user password
     *
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param string $username User Username
     * @param string $args New Status (enabled, disabled)
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function updatePassword(string $username, string $password)
    {
        $args['username'] = $username;
        $args['password'] = $password;

        $map = [
            ['name' => 'username',          'type' => 'string',     'required' => true],
            ['name' => 'password',          'type' => 'string',     'required' => true],
        ];

        return $this->execute('user/updatepassword/', $args, $map);
    }

    /**
     * Create User
     *
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param string $username User Username
     * @param string $args New Status (enabled, disabled)
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function create(string $username, string $password)
    {
        $args['username'] = $username;
        $args['password'] = $password;

        $map = [
            ['name' => 'username',          'type' => 'string',     'required' => true],
            ['name' => 'password',          'type' => 'string',     'required' => true],
        ];

        return $this->execute('user/create/', $args, $map);
    }

    /**
     * Delete User
     *
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param string $username User Username
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function delete(string $username)
    {
        $args['username'] = $username;

        $map = [
            ['name' => 'username',          'type' => 'string',     'required' => true],
        ];

        return $this->execute('user/delete/', $args, $map);
    }

    /**
     * Add domain to User
     *
     * * ! = required
     * ! domainName             string		Domain Name
     * - domain_ownercontacts   string
     * - domain_contacts        string
     * - domain_dns             string
     * - domain_gluerecords     string
     * - domain_dnssec          string
     * - domain_transferblock   string
     * - domain_authcode        string
     * - domain_others          string
     * - service_ftp            string
     * - service_subdomains     string
     * - service_redirects      string
     * - service_parking        string
     * - service_dnszone        string
     * - service_ddbb           string
     * - servicemail_accounts   string
     * - servicemail_alias      string
     * 
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param string $username User Username
     * @param array $args
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function addDomain(string $username, array $args = [])
    {
        $args['username'] = $username;

        $map = [
            ['name' => 'username',              'type' => 'string',     'required' => true],
            ['name' => 'domainName',            'type' => 'string',     'required' => true],
            ['name' => 'domain_ownercontacts',  'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'domain_contacts',       'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'domain_dns',            'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'domain_gluerecords',    'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'domain_dnssec',         'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'domain_transferblock',  'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'domain_authcode',       'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'domain_others',         'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'service_ftp',           'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'service_subdomains',    'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'service_redirects',     'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'service_parking',       'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'service_dnszone',       'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'service_ddbb',          'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'servicemail_accounts',  'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
            ['name' => 'servicemail_alias',     'type' => 'list',       'required' => false,    'list' => ['write', 'read', 'disabled']],
        ];

        return $this->execute('user/adddomain/', $args, $map);
    }

    /**
     * Delete domain to User
     * 
     * @link https://dev.dondominio.com/api/docs/api/
     *
     * @param string $username User Username
     * @param string $domainName Domain Name
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function deleteDomain(string $username, string $domainName)
    {
        $args['username'] = $username;
        $args['domainName'] = $domainName;

        $map = [
            ['name' => 'username',              'type' => 'string',     'required' => true],
            ['name' => 'domainName',            'type' => 'string',     'required' => true],
        ];

        return $this->execute('user/deletedomain/', $args, $map);
    }


}
