# DonDominio API Client for PHP
The DonDominio API Client for PHP is the easiest way to use the DonDominio API within your PHP application.

## Before you start
To use the DonDominio API, you will need first to [sign up for an API username & key](https://www.dondominio.com/products/api/). More information about the DonDominio API is available on the [Developer Portal](https://docs.dondominio.com/).

## Installation
To use the client, download the source code, unzip it and place it within your application. Then, include the `DonDominio.php` class and start using it right away:

```php
require_once('path/to/DonDominio.php');

$client = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));
```

Alternatively, use [Composer](http://www.getcomposer.org) to install it:

```json
{
	"require": {
		"dondominio/dondominio-php": "@stable"
	}
}
```

## Basic usage
**This is the recommended usage**. The DonDominio Client provides wrappers for each module in the DonDominio API. 

For example, to get your account information:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$response = $dondominio->account_info();

print_r($response->getResponseData());
```

`getResponseData()` returns an array containing the result of the API call. The [DonDominioResponse object documentation](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominioResponse) describes all available methods and formats.

## Advanced usage
The DonDominio Client allows you to call the API directly:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$data = $dondominio->call('account/info/', array());

$response = json_decode($data, true);

print_r($response['responseData']);
```

Note that you'll need to do all the validations and conversions by yourself. The [DonDominio Client documentation](https://github.com/DonDominio/DonDominioPHP/wiki/Client) covers this method as well.

## Examples
The [Examples section](https://github.com/DonDominio/DonDominioPHP/wiki/examples) of the Wiki has a few examples to get started.

## Documentation
The [DonDominioPHP Wiki](https://github.com/DonDominio/DonDominioPHP/wiki) covers the usage of every part of the Client:

* [DonDominio Client object]()
* [DonDominioResponse object]()
* [Specifying Contact Data]()

There's also documentation for each module wrapper:

* [Accouns](https://github.com/DonDominio/DonDominioPHP/wiki/Account-module)
* [Contacts](https://github.com/DonDominio/DonDominioPHP/wiki/Contact-module)
* [Domains](https://github.com/DonDominio/DonDominioPHP/wiki/Domain-module)
* [Tools](https://github.com/DonDominio/DonDominioPHP/wiki/Tool-module)