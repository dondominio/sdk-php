# cURL Wrapper
The DonDominio API Client features a cURL Wrapper to communicate with the DonDominio API using `POST` requests.

You can use this Wrapper to perform direct calls to the API. However, we encourage you to use the [DonDominio API Client object](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominio), which is easier to use and automatically performs many tasks.

## Usage
Instantiate a new `DonDominioClientPostCurl` object to use the cURL Wrapper. The constructor takes an array of options.

For example, to instantiate a cURL Client with debug enabled:

```php
$curlWrapper = new DonDominioClientPostCurl(array(
	'debug' => true
));
```

## Options
The cURL client accepts an array with the following options:

| Option | Type | Required? | Default | Description |
| ------ | ---- | --------- | ------- | ----------- |
| endpoint | string | No |  | URL where the DonDominio API is located |
| port | integer | No | 443 | Port to use when connecting to the API |
| timeout | integer | No | 15 | Connection timeout |
| debug | boolean | No | false | Enable or disable debug logging |
| debugOutput | string | No | NULL | Target of the log information<br>You can provide a filename to store the log, or set this to `NULL` to log to stdout. Setting it to `error_log` will use PHP's built-in error logging system. |
| verifySSL | boolean | No | false | Verify the SSL certificate from the server |
| format | string | No | json | Output format<br>**json:** JSON string<br>**xml:** XML string |
| pretty | boolean | No | false | Preformat the output information |

## Available methods

### execute()
Performs an API call using the cURL client.

Check the [API documentation](https://docs2.dondominio.com/api/) to learn more about the available API calls.

##### Call
```php
string execute ( string $url [, array $args = [] )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| url | string | **Yes** | | API URL |
| args | array | No | *array()* | Arguments to be passed to the API call |

##### Response
String containing the response from the API.