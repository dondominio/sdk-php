<?php

/**
 * Wrapper for the DonDominio Tool API module.
 * Please read the online documentation for more information before using the module.
 * 
 * @package DonDominioPHP
 * @subpackage Wrappers
 */
 
namespace Dondominio\API\Wrappers;

class Tool extends \Dondominio\API\Wrappers\AbstractWrapper
{
    /**
     * Tests the connectivity to the API.
     *
     * @link https://dondominio.dev/es/api/docs/api/#hello-tool-hello
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function hello()
    {
        return $this->execute('tool/hello/', []);
    }

    /**
     * Converts a string or domain name to unicode or punycode (IDNA).
     *
     * @link https://dondominio.dev/es/api/docs/api/#idn-converter-tool-idnconverter
     * @link http://en.wikipedia.org/wiki/Internationalized_domain_name#Example_of_IDNA_encoding
     *
     * @param string $query String to be converted
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function idnConverter($query)
    {
        if (empty($query)) {
            $query = '';
        }

        $_params = ['query' => $query];

        $map = [
            ['name' => 'query',   'type' => 'string',   'required' => true]
        ];

        return $this->execute('tool/idnconverter/', $_params, $map);
    }

    /**
     * Generates suggestions for domains based on a word.
     * Accepts an associative array with the following parameters:
     *
     * ! = required
     * ! query				string		The word to search for
     * - language			string		One of: es, en, zh, fr, de, kr, pt, tr (defaults to es)
     * - tlds				string		One of: com, net, tv, cc, es, org, info, biz, eu (defaults to "com,net,tv,es"); separate with commas
     *
     * @link https://dondominio.dev/es/api/docs/api/#domain-suggests-tool-domainsuggests
     *
     * @param	array		$args		Associative array of parameters (see table)
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function domainSuggests(array $args = [])
    {
        $_params = $args;

        $map = [
            ['name' => 'query',     'type' => 'string', 'required' => true],
            ['name' => 'language',  'type' => 'string', 'required' => false],
            ['name' => 'tlds',      'type' => 'string', 'required' => false]
        ];

        return $this->execute('tool/domainsuggests/', $_params, $map);
    }

    /**
     * Get various types of code tables used by the API.
     *
     * @link https://dondominio.dev/es/api/docs/api/#get-table-tool-gettable
     *
     * @param string $tableType Table to get
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function getTable($tableType)
    {
        if (empty($tableType)) {
            $tableType = '';
        }

        $_params = ['tableType' => $tableType];

        $map = [
            ['name' => 'tableType', 'type' => 'list',   'required' => true, 'list' => ['countries', 'es_juridic']]
        ];

        return $this->execute('tool/gettable/', $_params, $map);
    }

    /**
     * Decode the parameters contained in a CSR.
     * Maintained function to preserve backward compatibility with previous versions
     *
     * @link https://dondominio.dev/es/api/docs/api/#csr-decode-tool-csrdecode
     * @link http://en.wikipedia.org/wiki/Certificate_signing_request
     *
     * @param string $csrData CSR data (including ---BEGIN--- and ---END---)
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function csrDecode($csrData)
    {
        return $this->master->ssl_csrDecode($csrData);
    }

    /**
     * Test the DNS servers for a domain using the Domain Information Groper.
     * Accepts an associative array with the following parameters:
     *
     * @link https://dondominio.dev/es/api/docs/api/#dig-tool-dig
     * @link https://en.wikipedia.org/wiki/Dig_(command)
     *
     * ! = required
     * ! query			string		Domain/query to test
     * ! type			list		One of: A, AAAA, ANY, CNAME, MX, NS, SOA, TXT or CAA
     * ! nameserver		IPv4		DNS server to use to test the domain
     *
     * @param array $args Associative array of parameters
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function dig(array $args = [])
    {
        $map = [
            ['name' => 'query',         'type' => 'string', 'required' => true],
            ['name' => 'type',          'type' => 'list',	'required' => true, 'list' => ['A', 'AAAA', 'ANY', 'CNAME', 'MX', 'NS', 'SOA', 'TXT', 'CAA']],
            ['name' => 'nameserver',    'type' => 'ipv4',	'required' => true]
        ];

        return $this->execute('tool/dig/', $args, $map);
    }

    /**
     * Checks the domain zone.
     * Accepts an associative array with the following parameters:
     *
     * @link https://dondominio.dev/es/api/docs/api/#zonecheck-tool-zonecheck
     *
     * ! = required
     * ! nameservers	string	 	Comma-separated list of DNS servers (min 2, max 7).
     *
     * @param string $domain Domain to be checked
     * @param array $args Associative array of parameters
     *
     * @return	\Dondominio\API\Response\Response
     */
    protected function zonecheck($domain, array $args = [])
    {
        $_params = array_merge([
            'domain' => $domain
        ], $args);

        $map = [
            ['name' => 'domain',        'type' => 'domain', 'required' => true],
            ['name' => 'nameservers',   'type' => 'string', 'required' => true]
        ];

        return $this->execute('tool/zonecheck/', $_params, $map);
    }
}