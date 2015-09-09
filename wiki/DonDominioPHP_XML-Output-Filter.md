# XML Output Filter
This is an [Output Filter](https://github.com/DonDominio/DonDominioPHP/wiki/Output-Filters) for XML strings.

## Usage
Use it with `DonDominioResponse`, via the `output()` method:

```php
$options = array();

$data = $response->output('XML', $options);
```

## Options

| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| pretty | boolean | No | false | Outputs the XML preserving the format instead of on a single line (adding tabs and spaces) |

## Example output
PHP:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');

$xml = $domainCheck->output('XML', array('pretty'=>true));

echo $xml;
```

Output:

```xml
<?xml version="1.0"?>
<data>
  <domains>
    <0>
      <name>default.com</name>
      <punycode>default.com</punycode>
      <tld>com</tld>
      <available/>
      <premium/>
      <price>9.95</price>
      <currency>EUR</currency>
    </0>
  </domains>
</data>
```