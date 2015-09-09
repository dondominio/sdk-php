<?php

/**
 * XML output filter for DonDominio API responses.
 * @package DonDominioPHP
 * @subpackage OutputFilters
 */

/**
 * OutputFilter base class.
 */
require_once('OutputFilter.php');

/**
 * Generic Output Filters interface.
 */
require_once('OutputFilterInterface.php');

/**
 * XML output filter for DonDominio API responses.
 */
class OutputFilterXML extends OutputFilter implements OutputFilterInterface
{
	protected $options = array(
		'pretty' => false
	);
	
	/**
	 * Render a provided resultset in XML.
	 * @param array $result Array containing the response returned by the API
	 * @return string Valid XML string
	 */
	public function render($result)
	{
		if(!is_array($result)) return false;
		
		$xml = new SimpleXMLElement('<data/>');
		
		$this->toXML($xml, $result);
		
		if($this->getOption('pretty') == false){
			return $xml->asXML();
		}
		
		$dom = dom_import_simplexml($xml)->ownerDocument;
		$dom->preserveWhiteSpace = true;
		$dom->formatOutput = true;
		
		return $dom->saveXML();
	}
	
	/**
	 * Recursively convert an Array to a valid XML object.
	 * @param SimpleXMLElement $object XML object that will receive the data
	 * @param array $data Array containing data to be converted
	 */
	protected function toXML(SimpleXMLElement $object, array $data)
	{   
		foreach ($data as $key => $value){   
			if(is_array($value)){   
				$new_object = $object->addChild($key);
				$this->toXML($new_object, $value);
			}else{   
				$object->addChild($key, $value);
			}
		}
	}
}

?>