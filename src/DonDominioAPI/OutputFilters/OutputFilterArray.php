<?php

/**
 * JSON output filter for DonDominio API responses.
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

/**
 * OutputFilter base class.
 */
require_once('OutputFilter.php');

/**
 * OutputFilter interface.
 */
require_once('OutputFilterInterface.php');

/**
 * Array output filter for DonDominio API responses.
 */
class OutputFilterArray extends OutputFilter implements OutputFilterInterface
{
	protected $options = array();
	
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

?>