<?php

/**
 * Exception types used by the DonDominio API Client.
 * @package DonDominioPHP
 * @subpackage Exceptions
 */

namespace Dondominio\API\Exceptions;

class Error extends \Exception
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
        parent::__construct((is_array($message) ? implode("; ", $message) : $message), $code, $previous);
    }
}