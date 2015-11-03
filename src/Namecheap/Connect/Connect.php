<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Namecheap\Connect;

use Namecheap\NamecheapException;
use Namecheap\Response;

/**
 * Connects with Namecheaps API
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Connect
{
    /**
     * Defined environment (production or development)
     *
     * @param string $environment
     */
    private $environment;

    /**
     * Namecheap API key
     *
     * @param string $api_key
     */
    private $api_key;

    /**
     * Namecheap username
     *
     * @param string $api_username
     */
    private $api_username;

    /**
     * Namecheap username
     *
     * @param string $username
     */
    private $username;

    /**
     * Clients IP address
     *
     * @param string $client_ip
     */
    private $client_ip;

    /**
     * Path to SSL certificate to be used in cURL requests for https://
     *
     * @param string $ssl_certificate_path
     */
    private $ssl_certificate_path;

    /**
     * Namecheap API endpoints corresponding with environment value
     *
     * @param array $namecheap_urls
     */
    private $namecheap_urls = array(
            'production' => 'https://api.namecheap.com/xml.response',
            'development' => 'https://api.sandbox.namecheap.com/xml.response'
        );

    /**
     * Builds object options.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $config = $this->getDefaultConfiguration($config);

        $this->setEnvironment($config['environment']);
        $this->setApiKey($config['api_key']);
        $this->setApiUsername($config['api_username']);
        $this->setUsername($config['username']);
        $this->setClientIp($config['client_ip']);
        $this->setSslCertificatePath($config['ssl_certificate_path']);
    }

    /**
     * Builds default config from custom config.
     *
     * @param array $config
     *
     * @return array $config
     */
    private function getDefaultConfiguration($config)
    {
        $required_options = array(
                'environment',
                'api_key',
                'api_username',
                'client_ip'
            );

        $optional_parameters = array(
                'username' => null,
                'ssl_certificate_path' => preg_replace('/\/[^\/]+$/', '/Certificates', __DIR__).'/ca-bundle.crt'
            );

        foreach($config as $option => $value) {
            if(!in_array($option, $required_options)) {
                throw new \Exception('Missing configuration option `'.$option.'`');
            }
        }

        foreach($optional_parameters as $option => $value) {
            if(!isset($config[$option])) {
                $config[$option] = $value;
            }
        }

        return $config;
    }

    /**
     * Sets environment property
     *
     * @param string $environment
     *
     * @throws \Exception if passed environment is invalid
     */
    public function setEnvironment($environment)
    {
        if(!isset($this->namecheap_urls[$environment])) {
            throw new \Exception('Invalid value provided for `environment`');
        }
        $this->environment = $environment;
    }

    /**
     * Sets api_key property
     *
     * @param string $api_key
     *
     * @throws \Exception if passed api_key is not a string
     */
    public function setApiKey($api_key)
    {
        if(is_string($api_key)) {
            $this->api_key = $api_key;
        } else {
            throw new \Exception('Invalid value for `api_key` expecting string');
        }
    }

    /**
     * Sets api_username property
     *
     * @param string $api_username
     *
     * @throws \Exception if passed api_username is not a string
     */
    public function setApiUsername($api_username)
    {
        if(is_string($api_username)) {
            $this->api_username = $api_username;
        } else {
            throw new \Exception('Invalid value for `api_username` expecting string');
        }
    }

    /**
     * Sets username property
     *
     * @param string $username defaults to null
     *
     * @throws \Exception if passed username is not a string
     */
    public function setUsername($username = null)
    {
        if($username !== null) {
            if(is_string($username)) {
                $this->username = $username;
            } else {
                throw new \Exception('Invalid value provided for `username`, expecting string');
            }
        } else {
            $this->username = $username;
        }
    }

    /**
     * Sets client_ip property
     *
     * @param string $client_ip
     *
     * @throws \Exception if passed client_ip is not a valid IPv4 address
     */
    public function setClientIp($client_ip)
    {
        if(is_string($client_ip)) {
            // IPv6 not implemented yet
            $ipv4_pattern = '/^(?:(?:[1-9]{1,2}|1[0-9]{2}|(?:2[0-4]{1}[0-9]{1}|25[0-4]{1}))\.){3}(?:[0-9]{1,2}|1[0-9]{2}|(?:2[0-4]{1}[0-9]{1}|25[0-4]{1}))$/';
            if(preg_match($ipv4_pattern, $client_ip) === 0) {
                throw new \Exception('Invalid IPv4 value specified for `client_ip`');
            } else {
                $this->client_ip = $client_ip;
            }
        } else {
            throw new \Exception('Invalid value for `client_ip` expecting string');
        }
    }

    /**
     * Sets ssl_certificate_path property
     *
     * @param string $path
     *
     * @throws \Exception if passed path is not a string or file doesn't exist at specified path
     */
    public function setSslCertificatePath($path)
    {
        if(is_string($path)) {
            if(file_exists($path)) {
                $this->ssl_certificate_path = $path;
            } else {
                throw new \Exception('SSL certificate not found at `'.$path.'`');
            }
        } else {
            throw new \Exception('Invalid value for `path` expecting string');
        }
    }

    /**
     * Gets environment property
     *
     * @return string $environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Gets api_key property
     *
     * @return string $api_key
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Gets api_username property
     *
     * @return string $api_username
     */
    public function getApiUsername()
    {
        return $this->api_username;
    }

    /**
     * Gets username property
     *
     * @return string $username or if $username is null will get api_username
     */
    public function getUsername()
    {
        if($this->username === null) {
            $username = $this->getApiUsername();
        } else {
            $username = $this->username;
        }
        return $username;
    }

    /**
     * Gets client_ip property
     *
     * @return string $client_ip
     */
    public function getClientIp()
    {
        return $this->client_ip;
    }

    /**
     * Gets ssl_certificate_path property
     *
     * @return string $ssl_certificate_path
     */
    public function getSslCertificatePath()
    {
        return $this->ssl_certificate_path;
    }

    /**
     * Exposed method to process API request
     *
     * @param string $command
     * @param array $parameters
     *
     * @return array $response
     */
    public function request($command, $parameters = array())
    {
        $url = $this->namecheap_urls[$this->getEnvironment()];
        $request_params = array(
                'ApiUser' => $this->getApiUsername(),
                'ApiKey' => $this->getApiKey(),
                'Username' => $this->getUsername(),
                'ClientIp' => $this->getClientIp(),
                'Command' => $command
            );

        $params_string = $this->stringifyParameters($request_params, $parameters);
        $response = $this->executeRequest($url, $params_string);
        return $response;
    }

    /**
     * Process API request from given URL
     *
     * @param string $url
     *
     * @throws \Exception on cURL errors
     * @throws \Namecheap\NamecheapException Namecheap errors
     *
     * @return array $response
     */
    private function executeRequest($url, $parameters)
    {
        $exploded_parameters = explode('&', $parameters);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_USERAGENT, 'LewisNelson/Namecheap-interface');
        curl_setopt($ch, CURLOPT_POST, count($exploded_parameters));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, $this->getSslCertificatePath());
        $response = curl_exec($ch);
        $curl_errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if($curl_errno > 0) {
            throw new \Exception('cURL error - ('.$curl_errno.'): `'.$curl_error.'`');
        }

        $response = NamecheapResponse::create($response, $url, $exploded_parameters);
        $formatted_response = $response->format();

        if($formatted_response['status'] !== 'ok') {
            throw new NamecheapException(new Response($formatted_response['status'], $formatted_response['request_info'], $formatted_response['response']));
        }

        return $formatted_response;
    }

    /**
     * Build the URL parameters for API url from arrays of parameters
     *
     * @param array $default_params
     * @param array $parameters
     *
     * @throws \Exception on invalid parameters and if parameters is not an array
     *
     * @return string $return_string
     */
    private function stringifyParameters($default_params, $parameters)
    {
        $error_message = 'Invalid value specified for `parameters` expecting array';
        $return_strings = array();
        if(!is_array($parameters)) {
            throw new \Exception($error_message);
        }

        $invalid_parameters = array(
                'ApiUser',
                'ApiKey',
                'Username',
                'ClientIp',
                'Command'
            );

        foreach($default_params as $parameter => $value) {
            $return_strings[] = $parameter.'='.$value;
        }

        foreach($parameters as $parameter => $value) {
            if(is_array($parameter)) {
                throw new \Exception($error_message);
            }

            if(in_array($parameter, $invalid_parameters)) {
                throw new \Exception('Invalid parameter `'.$parameter.'` cannot redeclare primary parameter');
            }

            $return_strings[] = $parameter.'='.$value;
        }

        $return_string = implode('&', $return_strings);

        return $return_string;
    }
}

?>