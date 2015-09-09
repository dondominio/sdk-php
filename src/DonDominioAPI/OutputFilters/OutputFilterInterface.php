<?php

/**
 * Interface for output filters.
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

/**
  * Interface for output filters.
  */
interface OutputFilterInterface
{
	/**
	 * Output the provided information in the corresponding format.
	 * @param array $result Array containing the information returned by the API
	 * @return Equivalent version in the corresponding format of the provided information
	 */
	public function render($result);
}

?>