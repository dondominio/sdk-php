# Tool module
The Tool module provides general utilities.

## Accessing the module
All the methods from this module are prefixed with `tool_`. For example:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$toolHello = $dondominio->tool_hello();
```

All methods will return a [DonDominioResponse object](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominioResponse) containing the data -or any errors- returned by the API call.

## Available methods

### tool_hello()
Checks that the API is reachable and is working properly.

##### Call
```php
DonDominioResponse tool_hello ()
```

##### Parameters
None

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| ip | string | IP address accessing the API |
| lang | string | Language being used by the API |
| version | string | Current API version |

-

### tool_idnConverter()
Converts domain names between Unicode and Punycode ([IDNA format](http://en.wikipedia.org/wiki/Internationalized_domain_name#Example_of_IDNA_encoding)).

##### Call
```php
DonDominioResponse tool_idnConverter ( string $query )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| query | string | **Yes** | | Domain name to be converted |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| query | string | The original query made |
| unicode | string | The domain name in Unicode format |
| punycode | string | The domain name in Punycode format |

-

### tool_getTable()
Obtains tables of constants for reference used by some API modules.

##### Call
```php
DonDominioResponse tool_getTable ( string $tableType )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| tableType | string | **Yes** | | Table to get<br>**countries:** List of 2-character codes for all countries<br>**es_juridic:** List of juridic types used by the spanish government |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| items | array | Array containing the information from the table |

##### More information
Check the [API documentation](https://docs.dondominio.com/api/#section-3-3) for more information about tables.

-

### tool_csrDecode()
Decodes parameters inside a [CSR](http://en.wikipedia.org/wiki/Certificate_signing_request).

##### Call
```php
DonDominioResponse tool_csrDecode ( string $csrData )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| csrData | string | **Yes** | | CSR contents (including the `---BEGIN---` and `---END---` lines) |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| csrData | string | The CSR contents sent |
| commonName | string | CSR common name |
| organizationName | string | Name of the organization associated with the CSR |
| organizationalUnitName | string | Unit name associated with the CSR |
| countryName | string | 2-character code of the country |
| stateOrProvinceName | string | Name of the state or province of the company |
| localityName | string | Name of the locality of the company |
| emailAddress | string | Email address associated with the CSR |