<?php

/**
 * TXT output filter for DonDominio API responses.
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

namespace Dondominio\API\OutputFilters;

class OutputFilterTXT extends \Dondominio\API\OutputFilters\OutputFilter
	implements
		\Dondominio\API\OutputFilters\OutputFilterInterface
{
	protected $options = array(
		'indentation' => ' '
	);

	/**
	 * Every output filter must implement the "render" method to work.
	 */
	public function render($result){
		if(!is_array($result)) return false;
		
		return $this->toTXT($result);
	}

	/**
	 * This is a custom method created for this example.
	 */
	protected function toTXT($item, $indentation = 0){
		$txt = '';

		if(is_array($item)){
			foreach($item as $key=>$value){
				if(is_array($value)){
					$txt .= str_repeat($this->getOption('indentation'), $indentation) . "[$key]" . PHP_EOL . $this->toTXT($value, $indentation + 1);
				}else{
					$txt .= str_repeat($this->getOption('indentation'), $indentation) . str_pad($key, 12) . ': ' . $value . PHP_EOL;
				}
			}
		}

		return $txt;
	}
}