# Contact module
The Contact module gives access to the contacts stored in the account.

## Accessing the module
All the methods from this module are prefixed with `contact_`. For example:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

$contactList = $dondominio->contact_list();
```

All methods will return a [DonDominioResponse object](https://github.com/DonDominio/DonDominioPHP/wiki/DonDominioResponse) containing the data -or any errors- returned by the API call.

## Available methods

### contact_list()
Returns a list containing the contacts registered into the account.

##### Call
```php
DonDominioResponse contact_list ( [ array $parameters ] )
```

###### Heads up!
The `getList` method is called `list` when using the DonDominio client.

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| pageLength | integer | No | 1000 | Maximum number of items returned in a single call |
| page | integer | No | 1 | Current page of results |
| name | string | No | | Filter contacts by name |
| email | string | No | | Filter contacts by **exact** email address |
| country | string | No | | Filter contacts by [country code](https://docs.dondominio.com/country-codes/) |
| identNumber | string | No | | Filter contacts by **exact** id number |

##### Response
| Field | Field (Array) | Type | Description |
| ----- | ------------- | ---- | ----------- |
| queryInfo | | array | Information about the processed query |
| | page | integer | Current page |
| | pageLength | integer | Maximum number of items queried |
| | results | integer | Number of items returned |
| | total | integer | Total number of items available |
| contacts | | array | Contacts information - one item per contact |
| | contactID | string | Contact identification number in the DonDominio system |
| | contactType | string | Type of contact (`individual` or `organization`) |
| | contactName | string | Name of the contact |
| | identNumber | string | Id number of the contact |
| | email | string | Email address of the contact |
| | country | string | Country code of the contact |

-

### contact_getInfo()
Get all the information available from a contact.

##### Call
```php
DonDominioResponse contact_getInfo ( string $contactID [, string $infoType = 'data' ] )
```

##### Parameters
| Parameter | Type | Required? | Default | Description |
| --------- | ---- | --------- | ------- | ----------- |
| contactId | string | **Yes** | | Contact identification number in the DonDominio system |
| infoType | string | No | data | Type of information to obtain<br>**data**: Contact data |

##### Response
| Field | Type | Description |
| ----- | ---- | ----------- |
| **infoType = data** | | |
| *contactXYZ* | [ContactData](https://github.com/DonDominio/DonDominioPHP/wiki/contact) | Contact information in multiple fields|