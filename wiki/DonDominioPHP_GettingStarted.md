# DonDominio API Client for PHP
A simple library to use the DonDominio API from PHP.

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

## Calling the API
The DonDominio API currently has 4 modules:

| Module | Name | Description |
| ------ | ---- | ----------- |
| /account | Account | Operations on the customer/API account |
| /contact | Contact | Operarions on domain contacts |
| /domain | Domain | Operation on domains |
| /tool | Tool | General tools and helpers |

To use a module, instantiate the `DonDominio` object and call it using the following syntax:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$hello = $dondominio->tool_hello();
```

The syntax is the same for every module: `$client->module_command`.

## Getting responses back from the API
The DonDominio Client and the module wrappers return responses from the API wrapped in a `DonDominioResponse` object. The [DonDominioResponse class documentation](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominioResponse) of this wiki has more information on how this object works.

If you want to get output directly from the API, you may use the `DonDominioResponse` object as well, or instead call the API directly from the DonDominio Client. This method does not use module wrappers and requires you to validate parameters and responses by yourself. You can get more information about this method in the [DonDominio class documentation](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominioResponse).