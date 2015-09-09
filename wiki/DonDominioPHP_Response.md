# DonDominioResponse
An object representing the response from a command issued to the DonDominio API. Returned by the **Wrapper modules** of the DonDominio Client.

## Example

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$response = $dondominio->domain_check('example.com');

/*
 * Will output:
 * Array
 * (
 *   [domains] => Array
 *       (
 *           [0] => Array
 *               (
 *                   [name] => example.com
 *                   [punycode] => example.com
 *                   [tld] => com
 *                   [available] => bool(false)
 *                   [premium] => bool(false)
 *                   [price] => 9.95
 *                   [currency] => EUR
 *               )
 *
 *       )
 *
 * )
 */
print_r($response->output('Array'));
```

## Available methods

### success()
Returns whether the command was a success or not.

##### Call
```php
boolean success ()
```

##### Parameters
None

##### Response
Returns `true` if the command was a success, `false` otherwise.

-

### getErrorCode()
Gets the error code returned by the API, if any.

##### Call
```php
integer getErrorCode ()
```

##### Parameters
None

##### Response
The error code returned by the API, if any.

-

### getErrorCodeMsg()
Gets the error message associated to the error code returned by the API, if any.

##### Call
```php
string getErrorCodeMsg ()
```

##### Parameters
None

##### Response
The error message associated to the error code returned by the API, if any.

-

### getAction()
Gets the original command name (action) issued to the API.

##### Call
```php
string getAction ()
```

##### Parameters
None

##### Response
The command name (action) issued to the API.

-

### getVersion()
Gets the API version that responded to the command issued.

##### Call
```php
string getVersion ()
```

##### Parameters
None

##### Response
The API version that responded to the command issued.

-

### getResponseData()
Returns the data included with the API's response.

##### Call
```php
array getResponseData ()
```

##### Parameters
None

##### Response
An Array containing the information returned by the API. Check the documentation of each module to know which fields are returned by every call.

-

### get()
Returns a single item from the response data returned by the API.

##### Call
```php
mixed get ( string $key )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| key | string | **Yes** | | Name of the field to get the value from |

##### Response
The value stored in the array item returned from the API identified by `$key`. If `$key` is not found in the array, this method will return `false`.

-

### getRawResponse()
Returns the original response, as sent by the API.

##### Call
```php
string getRawResponse ()
```

##### Parameters
None

##### Response
The original response, as returned by the API command issued. Usually, this is a JSON string.

-

### output()
Outputs the response from the DonDominio API in the specified format.

##### Call
```php
mixed output ( string $format [, array $options = array() ] )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| format | string | **Yes** | | [Output Filter](https://github.com/DonDominio/DonDominioPHP/wiki/Output-Filters) chosen for the output information |
| options | array | No | | Associative array of options for the output filter |

##### Response
The response from the DonDominio API in the chosen format. More information about output filters is available on the [Output Filters section](https://github.com/DonDominio/DonDominioPHP/wiki/Output-Filters) of this Wiki.