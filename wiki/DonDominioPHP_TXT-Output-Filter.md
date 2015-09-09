# TXT Output Filter
This is an [Output Filter](https://github.com/DonDominio/DonDominioPHP/wiki/Output-Filters) for plain text (unformatted).

## Usage
Use it with `DonDominioResponse`, via the `output()` method:

```php
$options = array();

$data = $response->output('TXT', $options);
```

## Options

| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| indentation | string | No | *One space* | String used for indentation |

## Example output
PHP:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');

$txt = $domainCheck->output('TXT', array('indentation'=>'  '));

echo $txt;
```

Output:

```
[domains]
  [0]
    name        : default.com
    punycode    : default.com
    tld         : com
    available   : 
    premium     : 
    price       : 9.95
    currency    : EUR
```