# Array Output Filter
This is an [Output Filter](https://github.com/DonDominio/DonDominioPHP/wiki/Output-Filters) for PHP Arrays.

## Usage
Use it with `DonDominioResponse`, via the `output()` method:

```php
$options = array();

$data = $response->output('Array', $options);
```

## Options

The Array Output Filter has no options.

## Example output
PHP:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');

$array = $domainCheck->output('Array', array('pretty'=>true));

print_r($array);
```

Output:

```php
Array
(
    [domains] => Array
        (
            [0] => Array
                (
                    [name] => default.com
                    [punycode] => default.com
                    [tld] => com
                    [available] => 
                    [premium] => 
                    [price] => 9.95
                    [currency] => EUR
                )

        )

)

```