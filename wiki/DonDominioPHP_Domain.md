# Domain module
The Domain module gives access to information about domains and tools to create, transfer and manage them.

## Accessing the module
All the methods from this module are prefixed with `domain_`. For example:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$domainCheck = $dondominio->domain_check('example.com');
```

All methods will return a [DonDominioResponse object](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominioResponse) containing the data -or any errors- returned by the API call.

## Available methods

### domain_check()
Checks the availability of a domain.

**Please note:** Abusing this command can lead to an account suspension.

##### Call
```php
DonDominioResponse domain_check ( string $domain )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain to be checked |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name (in UTF-8) |
| punycode | string | Domain name in IDNA format |
| tld | string | Domain's TLD |
| available | boolean | Indicates if the domain is available to register |
| premium | boolean | Indicates if the domain is premium |
| price | float | Annual registry price |
| currency | string | Currency for the domain's price |

-

### domain_checkForTransfer()
Checks if a domain is available for transfer.

##### Call
```php
DonDominioResponse domain_checkForTransfer ( string $domain )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain to be checked |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name (in UTF-8) |
| punycode | string | Domain name in IDNA format |
| tld | string | Domain's TLD |
| transferavail | boolean | Indicates if the domain is available to transfer |
| transfermsg | string | Additional messages if not transferrable |
| price | float | Annual registry price |
| currency | string | Currency for the domain's price |

-

### domain_create()
Create/Register a domain.

###### Heads up!
Before issuing a `create` command, you should first **check the availability** with a `check` command. 

##### Call
```php
DonDominioResponse domain_create ( string $domain , array $parameters )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name to be registered |
| period | integer | No | | Period to register the domain<br>If not specified, will use the default for the TLD |
| premium | boolean | No | false | Must be `true` to register premium domains |
| nameservers | string | No | parking | DNS Servers<br>If `parking` is specified, the Parking & Redirection service will be enabled. Otherwise, must be a comma-separated DNS servers list, with a minimum of 2 and a maximum of 7 specified |
| *ownerContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | **Yes** | | Owner contact information |
| *adminContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Administrative contact information<br>If not specified, will use the owner contact information |
| *techContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Technical contact information<br>If not specified, will use the administrative contact information |
| *billingContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Billing contact information<br>If not specified, will use the administrative contact information |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| billing | | array | Billing information about the domain registered |
| | total | float | Total amount paid for the domain |
| | currency | string | Currency of the total amount |
| domains | | array | Information about the domain |
| | name | string | Domain name registered |
| | status | string | Current status of the domain |
| | tld | string | Domain's TLD |
| | tsExpir | string | Date of expiration of the domain |
| | domainID | integer | Internal identification for this domain |
| | period | integer | Period that the domain is registered for |

-

### domain_transfer()
Transfer an existing domain to DonDominio.

###### Heads up!
Before issuing a `transfer` command, you should first **check the availability** with a `check` command.

##### Call
```php
DonDominioResponse domain_transfer ( string $domain , array $parameters )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name to be registered |
| nameservers | string | No | parking | DNS Servers<br>If `parking` is specified, the Parking & Redirection service will be enabled. If `keepns` is specified, current DNS servers will be preserved. Otherwise, must be a comma-separated DNS servers list, with a minimum of 2 and a maximum of 7 specified. |
| authcode | string | No | | AuthCode for the domain, if required |
| *ownerContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | **Yes** | | Owner contact information |
| *adminContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Administrative contact information<br>If not specified, will use the owner contact information |
| *techContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Technical contact information<br>If not specified, will use the administrative contact information |
| *billingContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Billing contact information<br>If not specified, will use the administrative contact information |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| billing | | array | Billing information about the domain registered |
| | total | float | Total amount paid for the domain |
| | currency | string | Currency of the total amount |
| domains | | array | Information about the domain |
| | name | string | Domain name transferred |
| | status | string | Current status of the domain |
| | tld | string | Domain's TLD |
| | tsExpir | string | Date of expiration of the domain |
| | domainID | integer | Internal identification for this domain |
| | period | integer | Period that the domain is registered for |

-

### domain_update()
Update the information of a domain registered in the account.

##### Call
```php
DonDominioResponse domain_update ( string $domain , array $parameters )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to update |
| updateType | string | **Yes** | | Type of information to be updated<br>**contact:** Update contact information for the domain<br>**nameservers:** Update the nameservers for the domain<br>**transferBlock:** Enable or disable the transfer blocking for this domain<br>**block:** Enable or disable the modifications blocking<br>**whoisPrivacy:** Enable or disable the WhoisPrivacy service |
| **updateType = contact** | | | | |
| *ownerContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Owner contact information |
| *adminContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Administrative contact information<br>If not specified, will use the owner contact information |
| *techContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Technical contact information<br>If not specified, will use the administrative contact information |
| *billingContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Billing contact information<br>If not specified, will use the administrative contact information |
| **updateType = nameservers** | | | | |
| nameservers | string | **Yes** | | DNS servers<br>If `default` is specified, DonDominio's DNS servers for hosting will be assigned. Otherwise, must be a comma-separated DNS servers list, with a minimum of 2 and a maximum of 7 specified. |
| **updateType = transferBlock** | | | | |
| transferBlock | boolean | **Yes** | false | Enable or disable the transfer blocking |
| **updateType = block** | | | | |
| block | boolean | **Yes** | false | Enable or disable the modifications blocking |
| **updateType = whoisPrivacy** | | | | |
| whoisPrivacy | boolean | **Yes** | false | Enable or disable the WhoisPrivacy service |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name |
| status | string | Current status of the domain |
| tld | string | Domain's TLD |
| tsExpir | string | Date of expiration of the domain |
| domainID | string | Internal Domain ID |

-

### domain_updateNameServers()
Update the current DNS servers for a domain. Same as issuing an `update` command with `$updateType = 'nameservers'`.

##### Call
```php
DonDominioResponse domain_updateNameServers ( string $domain , array $nameservers )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to update |
| nameservers | array | **Yes** | | Array containing the DNS servers or an option<br>Nameservers must be specified as an array, with one nameserver per key (with a minimum of 2 and a maximum of 7)<br>If the only item in the array is `default`, DonDominio's DNS servers for hosting will be assigned |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name |
| status | string | Current status of the domain |
| tld | string | Domain's TLD |
| tsExpir | string | Date of expiration of the domain |
| domainID | string | Internal Domain ID |

-

### domain_updateContacts()
Update the current contacts for a domain. Same as issuing an `update` command with `$updateType = 'contacts'`.

##### Call
```php
DonDominioResponse domain_updateContacts ( string $domain , array $parameters )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to update |
| *ownerContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Owner contact information |
| *adminContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Administrative contact information<br>If not specified, will use the owner contact information |
| *techContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Technical contact information<br>If not specified, will use the administrative contact information |
| *billingContactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | No | | Billing contact information<br>If not specified, will use the administrative contact information |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name |
| status | string | Current status of the domain |
| tld | string | Domain's TLD |
| tsExpir | string | Date of expiration of the domain |
| domainID | string | Internal Domain ID |

-

### domain_glueRecordCreate()
Add a DNS Server associated to a domain (a gluerecord).

##### Call
```php
DonDominioResponse domain_glueRecordCreate ( string $domain , array $parameters )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to update |
| name | string | **Yes** | | Gluerecord name |
| ipv4 | string | **Yes** | | IP address for the gluerecord in IPv4 format |
| ipv6 | string | No | | IP address for the gluerecord in IPv6 format |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| name | | string | Domain name |
| status | | string | Current status of the domain |
| tld | | string | Domain's TLD |
| tsExpir | | string | Expiration date of the domain |
| gluerecords | | array | Information about the gluerecords in the domain, one per key |
| | name | string | Glurecord name |
| | ipv4 | string | IP address of the glurecord in IPv4 format |
| | ipv6 | string | IP address of the gluerecord in IPv6 format |

-

### domain_glueRecordUpdate()
Update a DNS Server associated to a domain (a gluerecord).

##### Call
```php
DonDominioResponse domain_glueRecordUpdate ( string $domain , array $parameters )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to update |
| name | string | **Yes** | | Gluerecord name |
| ipv4 | string | **Yes** | | IP address for the gluerecord in IPv4 format |
| ipv6 | string | No | | IP address for the gluerecord in IPv6 format |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| name | | string | Domain name |
| status | | string | Current status of the domain |
| tld | | string | Domain's TLD |
| tsExpir | | string | Expiration date of the domain |
| gluerecords | | array | Information about the gluerecords in the domain, one per key |
| | name | string | Glurecord name |
| | ipv4 | string | IP address of the glurecord in IPv4 format |
| | ipv6 | string | IP address of the gluerecord in IPv6 format |

-

### domain_glueRecordDelete()
Delete a DNS Server associated to a domain (a gluerecord).

##### Call
```php
DonDominioResponse domain_glueRecordDelete ( string $domain , string $name )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to update |
| name | string | **Yes** | | Gluerecord name |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| name | | string | Domain name |
| status | | string | Current status of the domain |
| tld | | string | Domain's TLD |
| tsExpir | | string | Expiration date of the domain |
| gluerecords | | array | Information about the gluerecords in the domain, one per key |
| | name | string | Glurecord name |
| | ipv4 | string | IP address of the glurecord in IPv4 format |
| | ipv6 | string | IP address of the gluerecord in IPv6 format |

-

### domain_list()
List/Search the domains registered in the account.

##### Call
```php
DonDominioResponse domain_list ( [ array $parameters ] )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| pageLength | integer | No | 1000 | Maximum number of items returned in a single call |
| page | integer | No | 1 | Current page of results |
| domain | string | No | | Exact domain to filter |
| word | string | No | | Filter domain list by substring |
| tld | string | No | | Filter by TLD |
| renewable | boolean | No | | Filter only by renewables/not renewables |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| queryInfo | | array | Information about the processed query |
| | page | integer | Current page |
| | pageLength | integer | Maximum number of items queried |
| | results | integer | Number of items returned |
| | total | integer | Total number of available items |
| domains | | array | Information about the domains found |
| | name | string | Domain name |
| | status | string | Domain status |
| | tld | string | Domain TLD |
| | tsExpir | string | Domain expiration date |
| | domainID | string | Internal Domain ID |

-

### domain_getInfo()
Get information about a domain in the account.

##### Call
```php
DonDominioResponse domain_getInfo ( string $domain , array $parameters )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to check |
| infoType | string | **Yes** | | Type of information to get<br>**status:** General information about the domain<br>**contact:** Contact information<br>**nameservers:** DNS Servers<br>**authcode:** Authcode for the domain<br>**service:** Information about the associated service or hosting<br>**gluerecords:** Gluerecords for this domain |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name |
| status | string | Current status of the domain |
| tld | string | Domain's TLD |
| tsExpir | string | Expiration date of the domain |
| domainID | string | Internal Domain ID |
| **infoType = status** | | |
| tsCreate | string | Creation date |
| renewable | boolean | Indicates if the domain is renewable |
| modifyBlock | boolean | Indicates if the domain can't be modified |
| transferBlock | boolean | Indicates if the domain can't be transferred |
| whoisPrivacy | boolean | Indicates if the WhoisPrivacy service is enabled |
| authcodeCheck | boolean | Indicates if the authcode can be obtained with `$infoType = 'authcode'` |
| serviceAssociated | boolean | Indicates if this domain has a service or hosting associated |
| **infoType = contact** | | |
| contactOwner | array | Array containing all the information of the owner contact |
| contactAdmin | array | Array containing all the information of the administrative contact |
| contactTech | array | Array containing all the information of the technical contact |
| contactBilling | array | Array containing all the information of the billing contact |
| **infoType = nameservers** | | |
| nameservers | array | Array containing all the DNS Servers for the domain |
| **infoType = authcode** | | |
| authcode | string | AuthCode for the domain, if available |
| **infoType = service** | | |
| service | array | Array containing all the information of the associated service, if available |
| **infoType = gluerecords** | | |
| gluerecords | array | Array containing all the gluerecords for this domain, if available |

-

### domain_getAuthCode()
Get the AuthCode for a domain. Same as issuing a `getInfo` command with `$infoType = 'authcode'`.

##### Call
```php
DonDominioResponse domain_getAuthCode ( string $domain )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to check |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name |
| status | string | Current status of the domain |
| tld | string | Domain's TLD |
| tsExpir | string | Expiration date of the domain |
| domainID | string | Internal Domain ID |
| authcode | string | AuthCode for the domain, if available |

-

### domain_getNameServers()
Get the DNS Servers for a domain. Same as issuing a `getInfo` command with `$infoType = 'nameservers'`.

##### Call
```php
DonDominioResponse domain_getNameServers ( string $domain )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to check |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| name | | string | Domain name |
| status | | string | Current status of the domain |
| tld | | string | Domain's TLD |
| tsExpir | | string | Expiration date of the domain |
| domainID | | string | Internal Domain ID |
| nameservers | | array | Array containing all the DNS Servers for the domain<br>One item per DNS Server |
| | name | string | DNS Server name |
| | order | integer | Priority order |
| | ipv4 | string | IPv4 address of the DNS Server |

-

### domain_getGlueRecords()
Get the GlueRecords for a domain. Same as issuing a `getInfo` command with `$infoType = 'gluerecords'`.

##### Call
```php
DonDominioResponse domain_getGlueRecords ( string $domain )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to check |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| name | string | Domain name |
| status | string | Current status of the domain |
| tld | string | Domain's TLD |
| tsExpir | string | Expiration date of the domain |
| domainID | string | Internal Domain ID |
| gluerecords | array | Array containing all the gluerecords for this domain, if available |

-

### domain_renew()
Renew a domain in the account.

##### Call
```php
DonDominioResponse domain_renew ( string $domain , array $params = array() )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name (**or Domain ID**) to check |
| curExpDate | string | **Yes** | | Current expiration date, in `YYYYMMDD` or `YYYY-MM-DD` format |
| period | integer | No | | Number of years to renew the domain<br>By default, the minimum for the TLD |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| billing | | array | Billing information of the renewal |
| | total | float | Total amount billed for the renewal |
| | currency | string | Currency of the total amount |
| domains | | array | Information about the renewed domains |
| | name | string | Domain name |
| | status | string | Current status of the domain |
| | tld | string | Domain's TLD |
| | domainID | string | Internal ID of the domain |
| | tsExpir | string | Expiration date of the domain |
| | renewPeriod | string | Period renewed |

-

### domain_whois()
Make a query to the public Whois for a domain.

**Please note:** By default, only domains in the account can be queried.

##### Call
```php
DonDominioResponse domain_whois ( string $domain )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| domain | string | **Yes** | | Domain name to query for a Whois |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| domain | string | Domain name |
| whoisData | string | String returned from the Whois service containing the available information about the domain |
