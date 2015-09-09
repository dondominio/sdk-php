# Contact Data
Contact data for a domain can be specified by two different means: using a ContactID previously entered in the system, or specifying all the information about the contact when registering or transferring a domain.

**See also:** [Contact Module](https://github.com/DonDominio/DonDominioPHP/wiki/contact), [Domain module](https://github.com/DonDominio/DonDominioPHP/wiki/domain)

## Using the ContactID field
Whenever you need to specify contact information for a domain, you can use the ID of a contact previously created in your account.

A ContactID has the format XXX-00000, with varying length. You can obtain this ID either from the DonDominio/MrDomain control panel (accessing a domain in your account and then clicking in *Contacts*) or from the `Accounts` module, using the `list` command.

For example, when creating a domain you can specify the ContactID like this:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

//Creating a domain
$response = $dondominio->domain_create(
	'example.com',
	array(
		'ownerContactID' => 'XXX-23423'
	)
);
```

The API will automatically recognise and use the corresponding contact information.

## Specifying the full contact information
Or, you can specify the full list of contact information.

There are four types of contacts: owner, administrative, technical and billing.

###### Heads up!
When using the full contact information, do not add a `ContactID` field. Otherwise, all of the contact information provided will be ignored.

To specify the contact information, add the following parameters to the API call:

| Parameter | Type | Description |
| --------- | ---- | ----------- |
| ownerContactType | string | Type of contact<br>Can be `individual` or `organization` |
| ownerContactFirstName | string | First name |
| ownerContactLastName | string | Last name |
| ownerContactOrgName | string | Organization name<br>Required when `ownerContactType = 'organization' `; otherwise ignored |
| ownerContactOrgType | string | Type of organization in Spain<br>Required when `ownerContactType = 'organization'` and `ownerCountry = 'ES'`. See the [spanish juridic type codes table](https://docs.dondominio.com/api-esjuridic/) for more information. |
| ownerContactIdentNumber | string | Fiscal identification number (VAT number or equivalent) |
| ownerContactEmail | string | Contact's email address |
| ownerContactPhone | string | Contact's phone number |
| ownerContactFax | string | Contact's fax number<br>*Optional* |
| ownerContactAddress | string | Postal address |
| ownerContactPostalCode | string | Postal code |
| ownerContactCity | string | City |
| ownerContactState | string | State or province |
| ownerContactCountry | string | Country<br>Use the corresponding [country code](https://docs.dondominio.com/country-codes/) |

To specify administrative, technical, and billing contacts, replace the `owner` part of each field with `admin`, `tech`, or `billing`.

## More information
Check the [API documentation for creating domains](https://docs.dondominio.com/api/#section-5-3) for more details about how Contacts work in DonDominio.