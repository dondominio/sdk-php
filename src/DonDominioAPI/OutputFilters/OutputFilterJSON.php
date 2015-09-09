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
 * JSON output filter for DonDominio API responses.
 */
class OutputFilterJSON extends OutputFilter implements OutputFilterInterface
{
	protected $options = array(
		'pretty' => false
	);
	
	/**
	 * Render a provided resultset in JSON.
	 * @param array $result Array containing the response returned by the API
	 * @return string JSON string
	 */
	public function render($result)
	{
		if(!is_array($result)) return false;
		
		$json_options = null;
		
		if($this->options['pretty'] == true){
			$json_options = JSON_PRETTY_PRINT;
		}
		
		return json_encode($result, $json_options);
	}
}

?>