<?php

/**
 * Base for DonDominio API module wrappers.
 * Read the online documentation for the API before making any calls.
 *
 * @link https://dev.dondominio.com/api/docs/api/
 *
 * @package DonDominioPHP
 * @subpackage Wrappers
 */

namespace Dondominio\API\Wrappers;

abstract class AbstractWrapper
{
    protected $master;

    /**
     * Get the DonDominio client instance to use only one http client.
     * @param \Dondominio\API\API $master DonDominio client instance
     */
    public function __construct(\Dondominio\API\API $master)
    {
        $this->master = $master;
    }

    /**
     * Proxy a request to a method in this class and return its result.
     * @param string $method Requested method
     * @param array $args Arguments passed to the method
     * @return \Dondominio\API\Response\Response
     */
    public function proxy($method, array $args = [])
    {
        $class_name = __CLASS__;

        if (strpos('_', __CLASS__)) {
            $class_array = explode('_', __CLASS__);
            $class_name = $class_array[1];
        }

        if (!method_exists($this, $method)) {
            trigger_error('Method ' . $method . ' not found in ' . $class_name, E_USER_ERROR);
        }

        return call_user_func_array([$this, $method], $args);
    }

    /**
     * Output the response in the appropriate format.
     * @param string $response Response in JSON format
     * @return string|array|Dondominio\API\Response\Response
     */
    protected function output($response)
    {
        return new \Dondominio\API\Response\Response(
            $response,
            $this->master->getOption('response')
        );
    }

    /**
     * Simulates an error from the API for internal purposes.
     * This makes an error produced by the client compatible with the
     * DonDominioResponse object.
     * @param string $message Error message
     * @param integer $errorCode Optional error code
     * @return string
     */
    protected function error($errorCodeMsg, $errorCode = -1, array $messages = [])
    {
        return json_encode([
            'success' => false,
            'errorCode' => $errorCode,
            'errorCodeMsg' => $errorCodeMsg,
            'action' => '',
            'version' => '',
            'messages' => $messages
        ]);
    }

    /**
     * Validate the parameters passed to a call and return the result from the API call.
     * @param string $url URL to be called
     * @param array $params Parameters to be passed to the call
     * @param array $map Map of validations to perform against the parameters
     * @return \Dondominio\API\Response\Response
     */
    protected function execute($url, array $params = [], array $map = [])
    {
        $errors = [];

        if (count($map) > 0 && $this->master->getOption('autoValidate')) {
            $errors = $this->validate($params, $map);
        }

        if (is_array($errors) && count($errors) > 0) {
            return $this->output($this->error('Validation error', -1, $errors));
        }

        $output = $this->output($this->master->call($url, $params));

        //Check the version from the API call to see if it matches the version
        //of this client.
        if ($this->master->getOption('versionCheck') && !is_null($output->getVersion())) {
            $version = explode('.', $output->getVersion());

            if ($this->master->api_version_minor < $version[1] && $this->master->api_version_major == $version[0]) {
                trigger_error('This client is not up to date. It\'s recommended to update it to match the API version being used.', E_USER_WARNING);
            }

            if ($this->master->api_version_major < $version[0]) {
                trigger_error('This client is deprecated. You must update to the latest version.', E_USER_WARNING);
            }
        }

        return $output;
    }

    /**
     * Validate parameters passed to a call and return an array of the errors found.
     * @param array $params Parameters passed to the call
     * @param array $map Map of validations to perform against the parameters
     * @return array
     */
    protected function validate(array $params = [], array $map = [])
    {
        $errors = [];

        foreach ($map as $key => $parameter) {
            $hasNotParam = !array_key_exists($parameter['name'], $params) || (!is_bool($params[$parameter['name']]) && empty($params[$parameter['name']]));
            if ($parameter['required'] && $hasNotParam){
                if (array_key_exists('bypass', $parameter)) {
                    $bypass = $parameter['bypass'];

                    if (!array_key_exists($bypass, $params) || empty($params[$bypass])) {
                        $errors[] = sprintf('Parameter "%s" missing', $parameter['name']);
                    }
                } else {
                    $errors[] = sprintf('Parameter "%s" missing', $parameter['name']);
                }
            }

            if (array_key_exists($parameter['name'], $params)) {
                $value = $params[$parameter['name']];

                if (!is_null($value)) {
                    switch ($parameter['type']) {
                        case 'list':
                            if (!in_array($value, $parameter['list'])) {
                                $errors[] = sprintf(
                                    '"%s" is not a valid value for parameter "%s". Accepted values: "%s"',
                                    $value,
                                    $parameter['name'],
                                    implode('", "', $parameter['list'])
                                );
                            }
                            break;

                        case 'boolean':
                        case 'bool':
                            if (!is_bool($value)) {
                                $errors[] = sprintf('Parameter "%s" must be a boolean', $parameter['name']);
                            }
                            break;

                        case 'string':
                            if (!is_string($value)) {
                                $errors[] = sprintf('Parameter "%s" must be a string', $parameter['name']);
                            }
                            break;

                        case 'integer':
                        case 'int':
                            if (!is_integer($value)) {
                                $errors[] = sprintf('Parameter "%s" must be an integer', $parameter['name']);
                            }

                            if (isset($parameter['min']) && $value < intval($parameter['min'])) {
                                $errors[] = sprintf('Parameter "%s" must be %s or more', $parameter['name'], $parameter['min']);
                            }

                            if (isset($parameter['max']) && $value > intval($parameter['max'])) {
                                $errors[] = sprintf('Parameter "%s" must be %s or less', $parameter['name'], $parameter['max']);
                            }
                            break;

                        case 'float':
                            if (!is_float($value)) {
                                $errors[] = sprintf('Parameter "%s" must be a float', $parameter['name']);
                            }

                            if (isset($parameter['min']) && $value < floatval( $parameter['min'])) {
                                $errors[] = sprintf('Parameter "%s" must be %s or more', $parameter['name'], $parameter['min']);
                            }

                            if (isset($parameter['max']) && $value > floatval( $parameter['max'])) {
                                $errors[] = sprintf('Parameter "%s" must be %s or less', $parameter['name'], $parameter['max']);
                            }
                            break;

                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid email address', $parameter['name']);
                            }
                            break;

                        case 'phone':
                            if (!preg_match('/^\+(\d+)\.(\d+)$/i', $value)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid phone number, in +DD.DDDDDDDD... format', $parameter['name']);
                            }
                            break;

                        case 'url':
                            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid URL', $parameter['name']);
                            }
                            break;

                        case 'domain':
                            if (!$this->cname(strtolower($value))) {
                                $errors[] = sprintf('Parameter "%s" must be a valid domain name', $parameter['name']);
                            }
                            break;

                        case 'countryCode':
                            if (!preg_match("/^([A-Z][A-Z])$/i", $value)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid country code', $parameter['name']);
                            }
                            break;

                        case 'contactID':
                            if (!preg_match("/^([A-Z]+)(-)([0-9]+)$/i", $value)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid Contact ID', $parameter['name']);
                            }
                            break;

                        case 'date':
                            if (!preg_match("/^[0-9]{4}(-?)(0[1-9]|1[0-2])(-?)(0[1-9]|[1-2][0-9]|3[0-1])$/i", $value)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid date, in YYYYMMDD or YYYY-MM-DD format', $parameter['name']);
                            }
                            break;

                        case 'ipv4':
                            $options = FILTER_FLAG_IPV4 |  FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
                            if (!filter_var($value, FILTER_VALIDATE_IP, $options)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid IPv4 address', $parameter['name']);
                            }
                            break;

                        case 'ipv6':
                            $options = FILTER_FLAG_IPV6 |  FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
                            if (!filter_var($value, FILTER_VALIDATE_IP, $options)) {
                                $errors[] = sprintf('Parameter "%s" must be a valid IPv6 address', $parameter['name']);
                            }
                            break;
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Check if a provided string is a valid CNAME.
     * @param string $cname String to check
     * @return boolean
     */
    protected function cname($cname)
    {
        if (!preg_match('/^([a-z0-9\-\.]+)\.[a-z]{2,30}$/i', $cname)) {
            return false;
        }

        $double_punctuation = (
            strpos($cname, '..') !== false
            || strpos($cname, '-.') !== false
            || strpos($cname, '.-') !== false
        );

        if ($double_punctuation) {
            return false;
        }

        if (strpos($cname, '.') !== false && strpos($cname, '.') == 0) {
            return false;
        }

        if (strpos($cname, '-') !== false && strpos($cname, '-') == 0) {
            return false;
        }

        return true;
    }

    /**
     * Convert a multi-dimensional array of parameters (w/ contacts) to its
     * flattened equivalent.
     *
     * 		We take an array that looks like this:
     *			Array => [
     *				'domain' => 'example.com',
     *				'owner' => [
     *					'firstName' => 'John',
     *					'lastName' => 'Doe'
     *				]
     *			]
     *
     *		And flatten it so it looks like this:
     *			Array => [
     *				'domain' => 'example.com',
     *				'ownerContactFirstName' => 'John',
     *				'ownerContactLastName' => 'Doe'
     *			]
     *
     * @param array $args Associative array of arguments
     * @return array Flattened array
     */
    protected function flattenContacts(array $args = [])
    {
        $_params = [];

        foreach ($args as $section => $argument) {
            //We only parse arrays inside the array that match one of the four contact types
            if (is_array($argument) && in_array($section, ['owner', 'admin', 'billing', 'tech'])) {
                foreach ($argument as $key => $value) {
                    //Automagically add the 'Contact' word to the key
                    $_params[$section . 'Contact' . ucfirst($key)] = $value;
                }
            } else {
                $_params[$section] = $argument;
            }
        }

        //The flattened array
        return $_params;
    }
}