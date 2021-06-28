<?php

/**
 * The DonDominio API Client.
 * @package DonDominioPHP
 * @subpackage API
 */

namespace Dondominio\API;

class API
{
    /**
     * Target API version of this library.
     * Use $options['versionCheck'] to disable version checking.
     * @var string
     */
    public $api_version_major = '1';
    public $api_version_minor = '1';

    protected $client;

    /**
     * Wrappers for each module in the API.
     * @var object
     */
    protected $account;
    protected $contact;
    protected $domain;
    protected $tool;
    protected $service;
    protected $ssl;
    protected $user;

    /**
     * Array of options for the ch.
     * @var array
     */
    protected $options = [
        'endpoint' => 'https://simple-api.dondominio.net',
        'port' => 443,
        'apiuser' => '',
        'apipasswd' => '',
        'autoValidate' => true,
        'versionCheck' => true,
        'debug' => false,
        'timeout' => 15,
        'debugOutput' => null,
        'verifySSL' => false,
        'response' => [
            'throwExceptions' => true
        ],
        'userAgent' => []
    ];

    /**
     * Initializing the client.
     * @param array $options Array containing options for the client
     * @throws \Dondominio\API\Exceptions\Error if no user or password is present
     */
    public function __construct(array $options = [])
    {
        //Merging default & defined options
        $this->options = array_merge($this->options, $options);

        //Checking that we have an username & a password
        if (empty($this->options['apiuser']) || empty($this->options['apipasswd'])) {
            throw new \Dondominio\API\Exceptions\Error('You must provide an user and a password for the API');
        }

        //Modules
        $this->account = new \Dondominio\API\Wrappers\Account($this);
        $this->contact = new \Dondominio\API\Wrappers\Contact($this);
        $this->domain = new \Dondominio\API\Wrappers\Domain($this);
        $this->tool = new \Dondominio\API\Wrappers\Tool($this);
        $this->service = new \Dondominio\API\Wrappers\Service($this);
        $this->ssl = new \Dondominio\API\Wrappers\SSL($this);
        $this->user = new \Dondominio\API\Wrappers\User($this);
    }

    public function close()
    {
        if (is_object($this->client)) {
            $this->client->close();
        }
    }

    /**
     * Set an option.
     *
     * @param string $key Name of the option
     * @param mixed $value New value for the option
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Get an option.
     *
     * @param string $key Name of the option
     * @return mixed or null if not found
     */
    public function getOption($key)
    {
        if (!array_key_exists($key, $this->options)) {
            return null;
        }

        return $this->options[$key];
    }

    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new \Dondominio\API\Client\Client([
                'endpoint' => $this->options['endpoint'],
                'port' => $this->options['port'],
                'timeout' => $this->options['timeout'],
                'debug' => $this->options['debug'],
                'debugOutput' => $this->options['debugOutput'],
                'verifySSL' => $this->options['verifySSL'],
                'format' => 'json',
                'pretty' => false,
                'userAgent' => $this->options['userAgent'],
                'throwExceptions' => $this->options['response']['throwExceptions']
            ]);
        }

        return $this->client;
    }

    /**
     * Automatically call a method inside a module wrapper from within the client.
     * @param string $method Method called
     * @param array $args Arguments passed to the method
     * @return \Dondominio\API\Response\Response
     */
    public function __call($method, array $args = [])
    {
        if (strpos($method, '_' ) === false) {
            trigger_error('Invalid call: ' . $method, E_USER_ERROR);
        }

        list($class, $method) = explode('_', $method);

        if (!property_exists($this, $class)) {
            trigger_error('Undefined module: ' . $class, E_USER_ERROR);
        }

        return $this->$class->proxy($method, $args);
    }

    /**
     * Execute an API call through the client.
     * @param string $url API endpoint
     * @param array $args Arguments passed to the call
     * @return array
     */
    public function call($url, array $args = [])
    {
        if (!extension_loaded('curl')) {
            die("cURL library no available. Use \"info\" for more information.");
        }

        $params = array_merge([
            'apiuser' => $this->options['apiuser'],
            'apipasswd' => $this->options['apipasswd']
        ],$args);

        return $this->getClient()->execute($url, $params);
    }

    /**
     * Check for requirements and valid settings.
     */
    public function info()
    {
        $phpMinVersion = "5.3.0";
        $phpVersionCheck = version_compare(PHP_VERSION, $phpMinVersion, ">=");
        $osName = php_uname('s') ?: PHP_OS;
        $osVersion = php_uname('v');
        $curlCheck = extension_loaded('curl');
        $jsonCheck = extension_loaded('json');

        $ip = file_get_contents('https://api.ipify.org');

        print(PHP_EOL);

        printf(" Local IP address is %s" . PHP_EOL, $ip);

        print( PHP_EOL );

        print(" Requirements" . PHP_EOL);
        print(" ============" . PHP_EOL);

        printf(" PHP Version:\t\t\t%s\t%s" . PHP_EOL, ($phpVersionCheck ? "OK" : "X"), PHP_VERSION);
        printf(" Operating system:\t\t\t%s" . PHP_EOL, $osName);
        printf(" OS Version:\t\t\t\t%s" . PHP_EOL, $osVersion);
        printf(" cURL Enabled:\t\t\t%s" . PHP_EOL, ($curlCheck ? "OK" : "X"));
        printf(" JSON Enabled:\t\t\t%s" . PHP_EOL, ($jsonCheck ? "OK" : "X"));

        print( PHP_EOL );

        /*
         * Checking settings.
         */
        $uri = $this->options['endpoint'];
        $uriCheck = !empty($uri);
        $port = $this->options['port'];
        $portCheck = !empty($port);
        $user = $this->options['apiuser'];
        $userCheck = !empty($user);
        $pass = preg_replace("/[^.]/i", "*", trim( $this->options['apipasswd']));
        $passCheck = !empty($pass);

        print(" Settings" . PHP_EOL);
        print(" ========" . PHP_EOL);

        printf(" URI:\t\t\t\t\t%s\t%s" . PHP_EOL, ($uriCheck ? "OK" : "X"), $uri);
        printf(" Port:\t\t\t\t\t%s\t%s" . PHP_EOL, ($portCheck ? "OK" : "X"), $port);
        printf(" Username:\t\t\t\t%s\t%s" . PHP_EOL, ($userCheck ? "OK" : "X"), $user);
        printf(" Password:\t\t\t\t%s\t%s" . PHP_EOL, ($passCheck ? "OK" : "X"), $pass);
        printf(" Validate params:\t\t%s" . PHP_EOL, ($this->options['autoValidate'] ? 'Yes' : 'No'));
        printf(" Check new releases:\t%s" . PHP_EOL, ($this->options['versionCheck'] ? 'Yes' : 'No'));
        printf(" Debug mode:\t\t\t%s" . PHP_EOL, ($this->options['debug'] ? 'Yes' : 'No'));
        printf(" Debug output:\t\t\t%s" . PHP_EOL, ($this->options['debugOutput'] ? 'Yes' : 'No'));
        printf(" Request timeout:\t\t%d seconds" . PHP_EOL,	$this->options['timeout']);
        printf(" Verify SSL certs:\t\t%s" . PHP_EOL, ($this->options['verifySSL'] ? 'Yes' : 'No'));
        printf(" Throw exceptions:\t\t%s" . PHP_EOL, ($this->options['response']['throwExceptions'] ? 'Yes' : 'No'));

        print(PHP_EOL);

        $errors = [];

        try {
            if (!$phpVersionCheck) {
                $errors[] = "PHP Version %s or higher required. Your version is " . $phpMinVersion;
            }

            if (!$curlCheck) {
                $errors[] = "cURL library for PHP5 is required. More info: http://php.net/manual/en/book.curl.php";
            }

            if (!$jsonCheck) {
                $errors[] = "JSON library for PHP5 is required. More info: http://php.net/manual/en/book.json.php";
            }

            if (!$uriCheck) {
                $errors[] = "API URI cannot be blank. Check your API URI on https://www.dondominio.com/admin/account/api/";
            }

            if (!$portCheck) {
                $errors[] = "API Port cannot be blank. Check your API Port on https://www.dondominio.com/admin/account/api/";
            }

            if (!$userCheck) {
                $errors[] = "API Username cannot be blank. Check your API Username on https://www.dondominio.com/admin/account/api/";
            }

            if (!$passCheck) {
                $errors[] = "API Password cannot be blank. Set your API Password on https://www.dondominio.com/admin/account/api";
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    printf(' [!!] ' . $error . PHP_EOL);
                }

                throw new \Exception('Please, fix the indicated errors before using DonDominioAPI.');
            }

            print(" Connection test" . PHP_EOL);
            print(" ===============" . PHP_EOL);

            print(" Executing `tool_hello`..." . PHP_EOL);
            print(PHP_EOL);

            $response = $this->tool_hello();

            if (!$this->getOption('throwExceptions') && !$response->getSuccess()) {
                throw new \Exception(
                    sprintf("Connection failed with error (%s): %s ", $response->getErrorCode(), $response->getErrorCodeMsg())
                );
            }

            print(" [OK] Success!" . PHP_EOL);

            print(PHP_EOL);

            printf(" Local IP:\t\t\t%s" . PHP_EOL, $response->get('ip'));
            printf(" Language:\t\t\t%s" . PHP_EOL, $response->get('lang'));
            printf(" API Version:\t\t\t%s" . PHP_EOL, $response->get('version'));

            print(PHP_EOL);
        } catch (\Throwable $e) {
            print(PHP_EOL);
            print(' [ERROR] ' . $e->getMessage() . PHP_EOL);
            print(PHP_EOL);
        }
    }
}