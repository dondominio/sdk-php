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
        ];

        return $this->execute('user/list/', $args, $map);
    }

    /**
     * User list
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
}
