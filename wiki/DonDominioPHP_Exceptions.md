# Exceptions
The `DonDominioResponse` object can throw PHP Exceptions to help you catch errors returned from the API. The `DonDominio` object may also throw Exceptions when an error is encountered.

## Catching exceptions
Catch Exceptions as usual with PHP:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

try{
	$domainCheck = $dondominio->domain_check('notAValidDomain');
}catch(DonDominio_Error $e){
	die('Error found: ' . $e->getMessage());
}
```

You can also catch exceptions by type:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

try{
	$domainCheck = $dondominio->domain_check('notAValidDomain');
}catch(DonDominio_InvalidDomainName $e){
	die('The domain name is not valid');
}
```

You can even catch more than one type of exception at the same time:

```php
$dondominio = new DonDominio(array(
	'apiuser' => '00000-XXX',
	'apipasswd' => 'XXXXXXXXXXXX'
));

try{
	$domainCheck = $dondominio->domain_check('notAValidDomain');
}catch(DonDominio_InvalidDomainName $e){
	die('The domain name is not valid');
}catch(DonDominio_Domain_CheckError $e){
	die('Couldn\'t check the availability of the domain');
}catch(DonDominio_Error $e){
	die('Unexpected error: ' . $e->getMessage());
}
```

## Available exceptions
All exception types inherit the `DonDominio_Error` exception type. Each different error code returned by the API will throw the corresponding exception. More information on error codes can be found on the [online documentation of the API](https://docs2.dondominio.com/api/#section-7-1).

| Name | Description |
| ---- | ----------- |
| DonDominio_Error | Generic error |
| DonDominio_HttpError | Thrown by the cURL client |
| DonDominio_ValidationError | Thrown by the module wrappers when input data does not match specifications |
| DonDominio_UndefinedError | Undefined Error |
| DonDominio_SyntaxError | Syntax Error |
| DonDominio_ParameterFault | Syntax Error: Parameter Fault |
| DonDominio_InvalidParameter | Syntax Error: Invalid Parameter |
| DonDominio_ObjectOrAction_NotValid | Invalid Object or Action |
| DonDominio_ObjectOrAction_NotAllowed | Not Allowed Object or Action |
| DonDominio_ObjectOrAction_NotImplemented | Object or Action not yet implemented |
| DonDominio_Login_Required | Login is required to access the resource |
| DonDominio_Login_Invalid | Login information is not valid |
| DonDominio_Session_Invalid | Session information is not valid |
| DonDominio_Action_NotAllowed | The requested action is not allowed |
| DonDominio_Account_Blocked | The account using the API is blocked |
| DonDominio_Account_Deleted | The account using the API has been deleted |
| DonDominio_Account_Inactive | The account using the API is inactive |
| DonDominio_Account_NotExists | The account trying to access the API does not exists |
| DonDominio_Account_InvalidPass | The Password used for the account is invalid |
| DonDominio_Account_Filtered | The account using the API is filtered |
| DonDominio_Account_Banned | The account using the API has been banned for misuse |
| DonDominio_InsufficientBalance | Insufficient balance to perform the requested action |
| DonDominio_InvalidDomainName | Domain name not valid |
| DonDominio_TLD_NotSupported | The TLD provided is not supported by DonDominio |
| DonDominio_TLD_UnderMaintenance | The TLD provided is currently under maintentance |
| DonDominio_Domain_CheckError | Couldn't check the domain |
| DonDominio_Domain_TransferNotAllowed | Domain transfer blocked |
| DonDominio_Domain_WhoisNotAllowed | Domain whois blocked |
| DonDominio_Domain_WhoisError | Error requesting the domain whois |
| DonDominio_Domain_NotFound | The domain was not found in DonDominio |
| DonDominio_Domain_CreateError | Generic error while creating a domain |
| DonDominio_Domain_CreateError_Taken | The domain is already registered |
| DonDominio_Domain_CreateError_PremiumDomain | The domain is a premium one and the `premium` parameter was false |
| DonDominio_Domain_TransferError | Generic error while transferring a domain |
| DonDominio_Domain_RenewError | Error while renewing the domain |
| DonDominio_Domain_RenewNotAllowed | Domain renewal is not allowed |
| DonDominio_Domain_RenewBlocked | Domain renewal is blocked |
| DonDominio_Domain_UpdateError | Generic error while updating domain information |
| DonDominio_Domain_UpdateNotAllowed | Domain modification is not allowed |
| DonDominio_Domain_UpdateBlocked | Domain modification is blocked |
| DonDominio_Contact_NotExists | The requested contact does not exists |
| DonDominio_Contact_DataError | The contact has invalid information |