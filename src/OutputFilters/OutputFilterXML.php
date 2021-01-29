<?php

/**
 * XML output filter for DonDominio API responses.
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

namespace Dondominio\API\OutputFilters;

class OutputFilterXML extends \Dondominio\API\OutputFilters\OutputFilter
    implements
        \Dondominio\API\OutputFilters\OutputFilterInterface
{
    protected $options = [
        'pretty' => false
    ];

    /**
     * Render a provided resultset in XML.
     * @param array $result Array containing the response returned by the API
     * @return string Valid XML string
     */
    public function render($result)
    {
        if (!is_array($result)) {
            return false;
        }

        $xml = new \SimpleXMLElement('<data/>');

        $this->toXML($xml, $result);

        if (!$this->getOption('pretty')) {
            return $xml->asXML();
        }

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->preserveWhiteSpace = true;
        $dom->formatOutput = true;

        return $dom->saveXML();
    }

    /**
     * Recursively convert an Array to a valid XML object.
     * @param \SimpleXMLElement $object XML object that will receive the data
     * @param array $data Array containing data to be converted
     */
    protected function toXML(\SimpleXMLElement $object, array $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $new_object = $object->addChild($key);
                $this->toXML($new_object, $value);
            } else {
                $object->addChild($key, $value);
            }
        }
    }
}