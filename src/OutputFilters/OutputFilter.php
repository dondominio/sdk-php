<?php

/**
 * OutputFilter base for DonDominio PHP Client.
 *
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

namespace Dondominio\API\OutputFilters;

abstract class OutputFilter
{
    const TYPE_JSON = 'JSON';
    const TYPE_XML = 'XML';
    const TYPE_TXT = 'TXT';
    const TYPE_ARRAY = 'Array';

    /**
     * Options.
     * @var array
     */
    protected $options = [];

    /**
     * Initialize the output filter.
     * @param array $options Options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Return the value of an option.
     * @param string $key Option name
     * @return mixed or null if $key not found
     */
    public function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            return null;
        }

        return $this->options[$key];
    }

    /**
     * Set an option.
     * @param string $key Option name
     * @param string $value Option value
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Get a list of possible output filters
     * @return array List of possible output filters
     */
    public static function getOutputFilters()
    {
        return [
            static::TYPE_JSON => \Dondominio\API\OutputFilters\OutputFilterJSON::class,
            static::TYPE_XML => \Dondominio\API\OutputFilters\OutputFilterXML::class,
            static::TYPE_TXT => \Dondominio\API\OutputFilters\OutputFilterTXT::class,
            static::TYPE_ARRAY => \Dondominio\API\OutputFilters\OutputFilterArray::class
        ];
    }
}