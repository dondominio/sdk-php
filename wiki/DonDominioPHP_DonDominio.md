# Base API Client - The `DonDominio` class
The Base API Client for the DonDominio API is the `DonDominio` class. It's an standalone class that can query the API directly, but also works with module wrappers to provide `DonDominioResponse` objects generated from API calls.

## Class description

```php
DonDominio {
	__construct ( [ array $options = array() ] )
	void setOption ( string $key , string $value )
	mixed getOption ( string $key )
	string call ( string $url [, array $args = array() ] )
}
```
## Options
The `DonDominio` object accepts the following options inside an associative array:

| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| endpoint | string | No |  | URL where the DonDominio API is located |
| port | integer | No | 443 | Port to use when connecting to the API |
| apiuser | string | **Yes** | | User for the API |
| apipasswd | string | **Yes** | | Password for the API User |
| autoValidate | boolean | No | true | Validate type and values for input parameters |
| versionCheck | boolean | No | true | Check whether the API Client is outdated |
| timeout | integer | No | 15 | Timeout in seconds for the connection |
| verifySSL | boolean | No | false | Check the SSL certificate from the API |
| debug | boolean | No | false | Log debug information |
| debugOutput | string | No | *NULL* | Target of the log information<br>You can provide a filename to store the log, or set this to `NULL` to log to stdout. Setting it to `error_log` will use PHP's built-in error logging system. |
| response | array | No | | Array of options to pass to the `DonDominioResponse` object when using wrappers |

Additionally, the `response` option accepts the following options:

| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| throwExceptions | boolean | No | true | Enables or disables exceptions throwed from the API wrappers |

## Available methods

### __construct()
Initializes the class. Gets an associative array of options as an argument.

##### Call
```php
__construct ( [ array $options = array() ] )
```

##### Parameters
| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| options | array | No | *Default option set* | Options passed to the client

##### Response
None

### setOption()
Set an existing or new option in the client options.

##### Call
```php
void setOption ( string $key , string $value )
```

##### Parameters
| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| key | string | **Yes** | | Name of the option |
| value | string | **Yes** | | New value for the option |

##### Response
None

### getOption()
Returns the current value of an option.

##### Call
```php
mixed getOption ( string $key )
```

##### Parameters
| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| key | string | **Yes** | | Name of the option |

##### Response
Current value of this option.

### call()
Execute a command on the API.

##### Call
```php
string call ( string $url [, array $args = array() ] )
```

##### Parameters
| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| url | string | **Yes** | | URL of the API command to execute |
| args | array | No | *empty array* | Arguments of the API command |

##### Response
The result returned by the API (usually, a JSON string).