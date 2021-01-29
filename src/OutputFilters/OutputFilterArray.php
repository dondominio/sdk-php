<?php

/**
 * JSON output filter for DonDominio API responses.
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

namespace Dondominio\API\OutputFilters;

class OutputFilterArray extends \Dondominio\API\OutputFilters\OutputFilter
    implements
        \Dondominio\API\OutputFilters\OutputFilterInterface
{
    protected $options = [];

    /**
     * Render a provided resultset inside an Array.
     * This filter effectively does nothing : )
     * @param array $result Array containing the response returned by the API
     * @return array Array containing the response returned by the API
     */
    public function render($result)
    {
        //Leave it unchanged for now
        return $result;
    }
}