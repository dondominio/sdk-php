# Account module
The Account module gives access to information related to the account using the API.

## Accessing the module
All the methods from this module are prefixed with `account_`. For example:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$accountInfo = $dondominio->account_info();
```

All methods will return a [DonDominioResponse object](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominioResponse) containing the data -or any errors- returned by the API call.

## Available methods

### account_info()
Returns information from the account currently accessing the API.

##### Call
```php
DonDominioResponse account_info ()
```

##### Parameters
None

##### Response

| Field | Type | Description |
| ----- | ---- | ----------- |
| ClientName | string | Account holder's name |
| apiuser | string | API username |
| balance | float | Available balance in the account |
| threshold | float | Warning threshold for balance |
| currency | string | Balance currency |
| ip | string | Current IP address from the API client |