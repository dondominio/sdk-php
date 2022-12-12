<?php

/**
 * POST cURL client for the DonDominio API (using SIMPLE-POST).
 * @package DonDominioPHP
 * @subpackage Client
 */

namespace Dondominio\API\Client;

class Client implements \Dondominio\API\Client\Client_Interface
{
    /**
     * cURL instance used by the client.
     * @var resource
     */
    protected $ch;

    protected $userAgent = [
        'ClientPlatform' => 'PHP',
        'ClientVersion' => '',
        'PHPVersion' => PHP_VERSION,
        'OperatingSystem' => '',
        'OperatingSystemVersion' => ''
    ];

    /**
     * Array of options for the client.
     * @var array
     */
    protected $options = [
        'endpoint' => 'https://simple-api.dondominio.net',
        'port' => 443,
        'timeout' => 15,
        'debug' => false,
        'debugOutput' => null,
        'verifySSL' => false,
        'format' => 'json',
        'pretty' => false,
        'throwExceptions' => false
    ];

    /**
     * Merge options passed and initialize the client.
     */
    public function __construct(array $options = [])
    {
        //Merging default & defined options
        if (is_array($options)) {
            $this->options = array_merge($this->options, $options);
        }

        $this->userAgent['OperatingSystem'] = php_uname('s');
        $this->userAgent['OperatingSystemVersion'] = php_uname('r');

        $composerFile = @file_get_contents(implode(DIRECTORY_SEPARATOR, [dirname(dirname(__FILE__)), 'version.json']));
        $composer = @json_decode($composerFile, true);
        $this->userAgent['ClientVersion'] = (is_array($composer) && array_key_exists('version', $composer)) ? $composer['version'] : '';

        $this->userAgent = array_merge($this->userAgent, $options['userAgent']);

        $this->init();
    }

    /**
     * Call an API endpoint.
     * @param string $url URL to be requested
     * @param array $args Parameters to be submitted along the request
     * @throws \Dondominio\API\Exceptions\HttpError on connection error
     * @throws \Dondominio\API\Exceptions\Error on
     * @return array|string
     */
    public function execute($url, array $args = [])
    {
        $ch = $this->ch;

        $extraOptions = [
            'output-format' => $this->options['format'],
            'output-pretty' => $this->options['pretty']
        ];
        $params = array_merge($extraOptions, $args);

        curl_setopt($ch, CURLOPT_URL, trim($this->options['endpoint']) . '/' . trim($url));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $start = microtime(true);

        $this->log('Calling: ' . $this->options['endpoint'] . '/' . $url);
        $this->log('Parameters: ' . json_encode($params));

        if ($this->options['debug']) {
            //Saving cURL output to memory for later
            $curl_buffer = fopen('php://memory', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $curl_buffer);
        }

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);

        $time = microtime(true) - $start;

        if ($this->options['debug']) {
            //Reading cURL buffer contents
            rewind($curl_buffer);
            $this->log(stream_get_contents($curl_buffer));
            fclose($curl_buffer);
        }

        $this->log('Completed in ' . number_format( $time * 1000, 2 ) . 'ms');
        $this->log('Response: ' . $response);

        //Checking for errors in cURL
        if ($curl_errno !== 0) {
            if ($this->options['throwExceptions']) {
                throw new \Dondominio\API\Exceptions\HttpError('cURL error (' . $curl_errno . '): ' . $curl_error);
            }

            return json_encode([
                'success' => false,
                'action' => rtrim($url, "/"),
                'errorCode' => $curl_errno,
                'errorCodeMsg' => $curl_error
            ]);
        }

        return $response;
    }

    /**
     * Add an user agent to the array.
     *
     * @param string $value Name of the User Agent
     * @param string $version Version
     */
    public function addUserAgent($value, $version)
    {
        $this->userAgent[$value] = $version;
    }

    /**
     * Build the user agent string from the array.
     *
     * @return string
     */
    protected function buildUserAgent()
    {
        $userAgentString = '';

        foreach ($this->userAgent as $key => $value) {
            $userAgentString .= $key . '/' . $value . ';';
        }

        return $userAgentString;
    }

    /**
     * Initialize the cURL client.
     */
    protected function init()
    {
        if (!extension_loaded('curl')) {
            throw new \Exception('Curl Extension not found.');
        }

        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->buildUserAgent());
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        if (!$this->options['verifySSL']) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($this->ch, CURLOPT_PORT, $this->options['port']);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->options['timeout']);
        curl_setopt($this->ch, CURLOPT_VERBOSE, $this->options['debug']);
    }

    /**
     * Log a message to the selected logging system, if logging is enabled.
     * The logging system can be controlled using the "debugOutput" option.
     *
     * Use $output = 'error_log' to output to the default PHP error_log.
     * Use $output = 'php://stdout' to output to the default stdout.
     * Use $output with a filename to write the log to that file.
     *
     * @param string $message Message to be logged
     */
    protected function log($message)
    {
        if ($this->options['debug']) {
            $output = $this->options['debugOutput'] ?: 'php://stdout';

            if ($output == 'error_log') {
                //Log to default logging system
                error_log($message);
                return;
            }

            //Otherwise, log to file
            file_put_contents($output, '[' . date('m/d/Y H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
        }
    }

    public function close()
    {
        if ($this->ch) {
            curl_close($this->ch);
            $this->ch = null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
