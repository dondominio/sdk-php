# Output filters
An Output Filter is a special object that allows the DonDominio API Client to output information returned by the API in a variety of formats.

By default, the DonDominio API Client for PHP package includes a set of output filters that you can use right away. You can also use them as an example to build your own filters, if you need to.

## Output filters included with the DonDominio Client
By default, the DonDominioPHP project includes the following output filters:

##### [Array](https://github.com/DonDominio/DonDominioPHP/wiki/JSON-Output-Filter)
Outputs data as a PHP Array.

##### [JSON](https://github.com/DonDominio/DonDominioPHP/wiki/JSON-Output-Filter)
Outputs data as a JSON string.

##### [XML](https://github.com/DonDominio/DonDominioPHP/wiki/XML-Output-Filter)
Outputs data as a XML string.

##### [TXT](https://github.com/DonDominio/DonDominioPHP/wiki/TXT-Output-Filter)
Outputs data as plain text, suitable for logging or for displaying in the PHP CLI interface.

## Using output filters
Output Filters are available through the `DonDominioResponse` object. To use them, simply call the method `output<FORMAT>`. For example, to get the API response as a JSON:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');

//Output a JSON string
$json = $domainCheck->output('JSON');
```

This will result in a JSON string containing the information from the `domain_check()` call.

## Creating output filters
You can create a custom Output Filter and use it with the `DonDominioResponse` object easily.

The following example is a custom output filter that outputs information in plain text:

```php
<?php

//Abstract base class for output filters
require_once("OutputFilter.php");

//Interface for output filters
require_once("OutputFilterInterface.php");

/**
 * We need to extend the "OutputFilter" abstract class and implement the 
 * "OutputFilterInterface" on our custom output filter in order for it
 * to work.
 */
class OutputFilterTXT extends OutputFilter implements OutputFilterInterface
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
					$txt .= str_repeat($this->getOption('indentation'), $indentation) . "[$key]\r\n" . $this->toTXT($value, ++$indentation);
				}else{
					$txt .= str_repeat($this->getOption('indentation'), $indentation) . $key . ': ' . $value . "\r\n";
				}
			}
		}
		
		return $txt;
	}
}

?>
```

To use the custom output filter, save it with the same name as the class, with `.php` extension, and put it in the `DonDominio/outputFilters/` folder.

For example, the previous example, `OutputFilterTXT`, would be found in `DonDominio/outputFilters/OutputFilterTXT.php`. It's important to use the exact same name as in the output filter class, otherwise the `DonDominioResponse` object will be unable to find and use output filter.

Following the naming convention, your filter will be called `TXT`. You can now use the custom output filter when using the `DonDominioResponse` object.

## Using a custom output filter
To use your newly created output filter, just use the `output()` method from `DonDominioResponse`. This is a special method that automatically finds and uses the matching output filter. For example, to get the contents of a response in plain text, using the previous example, use the following syntax:

```php
$plainText = $responseObject->output('TXT');
```

`$plainText` will contain the response from the `DonDominioResponse` object in plain text.

Assuming that you have followed the previous instructions to create & enable the example output filter, this would be the full example to use it:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');

$txt = $domainCheck->output(
	'TXT',
	array(
		'indentation' => '>'
	)
);

echo $txt;
```

This code will output this:

```
[domains]
>[0]
>>name        : default.com
>>punycode    : default.com
>>tld         : com
>>available   : 
>>premium     : 
>>price       : 9.95
>>currency    : EUR
```