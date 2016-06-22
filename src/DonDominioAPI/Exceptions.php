<?php

/**
 * Exception types used by the DonDominio API Client.
 * @package DonDominioPHP
 * @subpackage Exceptions
 */

/**
 * Default exception type for DonDominio exceptions.
 */
class DonDominioAPI_Error extends Exception
{
	/**
	 * Handle arrays on $message.
	 * @param string|array $message Error message or array containing error messages
	 * @param integer $code Error code
	 * @param Exception $previous Previous exception, if applicable
	 */
	public function __construct($message, $code = 0, \Exception $previous = null)
	{
		//Message can contain an array of messages returned by the API call.
		parent::__construct((is_array($message)) ? implode("; ", $message) : $message, $code, $previous);
	}
}

/**
 * Http-specific exceptions.
 */
class DonDominioAPI_HttpError extends DonDominioAPI_Error {}

/**
 * Validation error.
 */
class DonDominioAPI_ValidationError extends DonDominioAPI_Error {}

/**#@+
 * Specific exception types for different exceptions returned by the API.
 */
class DonDominioAPI_UndefinedError extends DonDominioAPI_Error {}
class DonDominioAPI_SyntaxError extends DonDominioAPI_Error {}
class DonDominioAPI_SyntaxError_ParameterFault extends DonDominioAPI_SyntaxError {}
class DonDominioAPI_SyntaxError_InvalidParameter extends DonDominioAPI_SyntaxError {}
class DonDominioAPI_ObjectOrAction_NotValid extends DonDominioAPI_Error {}
class DonDominioAPI_ObjectOrAction_NotAllowed extends DonDominioAPI_Error {}
class DonDominioAPI_ObjectOrAction_NotImplemented extends DonDominioAPI_Error {}

class DonDominioAPI_Login_Required extends DonDominioAPI_Error {}
class DonDominioAPI_Login_Invalid extends DonDominioAPI_Error {}
class DonDominioAPI_Session_Invalid extends DonDominioAPI_Error {}

class DonDominioAPI_Action_NotAllowed extends DonDominioAPI_Error {}

class DonDominioAPI_Account_Blocked extends DonDominioAPI_Error {}
class DonDominioAPI_Account_Deleted extends DonDominioAPI_Error {}
class DonDominioAPI_Account_Inactive extends DonDominioAPI_Error {}
class DonDominioAPI_Account_NotExists extends DonDominioAPI_Error {}
class DonDominioAPI_Account_InvalidPass extends DonDominioAPI_Error {}
class DonDominioAPI_Account_Filtered extends DonDominioAPI_Error {}
class DonDominioAPI_Account_Banned extends DonDominioAPI_Error {}

class DonDominioAPI_InsufficientBalance extends DonDominioAPI_Error {}

class DonDominioAPI_InvalidDomainName extends DonDominioAPI_Error {}
class DonDominioAPI_TLD_NotSupported extends DonDominioAPI_Error {}
class DonDominioAPI_TLD_UnderMaintenance extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_CheckError extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_TransferNotAllowed extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_WhoisNotAllowed extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_WhoisError extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_NotFound extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_CreateError extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_CreateError_Taken extends DonDominioAPI_Domain_CreateError {}
class DonDominioAPI_Domain_CreateError_PremiumDomain extends DonDominioAPI_Domain_CreateError {}
class DonDominioAPI_Domain_TransferError extends DonDominioAPI_Error {}

class DonDominioAPI_Domain_RenewError extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_RenewNotAllowed extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_RenewBlocked extends DonDominioAPI_Error {}

class DonDominioAPI_Domain_UpdateError extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_UpdateNotAllowed extends DonDominioAPI_Error {}
class DonDominioAPI_Domain_UpdateBlocked extends DonDominioAPI_Error {}

class DonDominioAPI_Domain_VerificationStatus extends DonDominioAPI_Error {}

class DonDominioAPI_Contact_NotExists extends DonDominioAPI_Error {}
class DonDominioAPI_Contact_DataError extends DonDominioAPI_Error {}
class DonDominioAPI_Contact_VerificationStatus extends DonDominioAPI_Error {}

class DonDominioAPI_Service_NotFound extends DonDominioAPI_Error {}
class DonDominioAPI_Service_EntityNotFound extends DonDominioAPI_Error {}
class DonDominioAPI_Service_EntityLimitReached extends DonDominioAPI_Error {}
class DonDominioAPI_Service_EntityCreateError extends DonDominioAPI_Error {}
class DonDominioAPI_Service_EntityUpdateError extends DonDominioAPI_Error {}
class DonDominioAPI_Service_EntityDeleteError extends DonDominioAPI_Error {}
class DonDominioAPI_Service_CreateError extends DonDominioAPI_Error {}
class DonDominioAPI_Service_UpgradeError extends DonDominioAPI_Error {}
class DonDominioAPI_Service_RenewError extends DonDominioAPI_Error {}
class DonDominioAPI_Service_ParkingUpdateError extends DonDominioAPI_Error {}

class DonDominioAPI_Webconstructor_Error extends DonDominioAPI_Error {}
/**#@-*/

?>