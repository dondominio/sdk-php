# JSON Output Filter
This is an [Output Filter](https://github.com/DonDominio/DonDominioPHP/wiki/Output-Filters) for JSON strings.

## Usage
Use it with `DonDominioResponse`, via the `output()` method:

```php
$options = array();

$data = $response->output('JSON', $options);
```

## Options

| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| pretty | boolean | No | false | Outputs the JSON preserving the format instead of on a single line (adding tabs and spaces) |

## Example output
PHP:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');

$json = $domainCheck->output('JSON', array('pretty'=>true));

echo $json;
```

Output:

```json
{
    "domains": [
        {
            "name": "default.com",
            "punycode": "default.com",
            "tld": "com",
            "available": false,
            "premium": false,
            "price": 9.95,
            "currency": "EUR"
        }
    ]
}
```