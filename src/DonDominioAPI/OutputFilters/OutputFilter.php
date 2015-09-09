<?php

/**
 * OutputFilter base for DonDominio PHP Client.
 *
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

/**
  * OutputFilter base for DonDominio PHP Client.
  */
abstract class OutputFilter
{
	/**
	 * Options.
	 * @var array
	 */
	protected $options = array();
	
	/**
	 * Initialize the output filter.
	 * @param array $options Options
	 */
	public function __construct(array $options = array())
	{
		$this->options = array_merge(
			$this->options,
			$options
		);
	}
	
	/**
	 * Return the value of an option.
	 * @param string $key Option name
	 * @return mixed or null if $key not found
	 */
	public function getOption($key)
	{
		if(!array_key_exists($key, $this->options)){
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
}

?>