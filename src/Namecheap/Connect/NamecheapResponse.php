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

/**
 * Processes Namecheaps XML responses
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class NamecheapResponse
{
    /**
     * XML response from Namecheap API
     *
     * @param string
     */
    private $response;

    /**
     * URL used in request
     *
     * @param string
     */
    private $url;

    /**
     * Post data used in request
     *
     * @param array
     */
    private $post_data;

    /**
     * Create a formatted Namecheap response
     *
     * @param string $response
     * @param string $url
     * @param array $post_data
     *
     * @return \Namecheap\Connect\NamecheapResponse
     */
    public static function create($response, $url, $post_data)
    {
        $instance = self::instantiateSelf($response, $url, $post_data);
        return $instance;
    }

    /**
     * Formats the XML response
     *
     * @param string $response
     * @param string $url
     * @param array $post_data
     *
     * @throws \Exception if unable to parse response as a Namecheap response
     *
     * @return array $formatted_response
     */
    public function format()
    {
        $xml = new \SimpleXMLElement($this->response);
        $attributes = (array)$xml->attributes();
        $formatted_response = false;

        if(isset($attributes['@attributes']['Status'])) {
            $status = $attributes['@attributes']['Status'];
            if($status === 'ERROR') {
                $formatted_response = self::parseErrors($xml);
            } else if($status === 'OK') {
                $formatted_response = self::parseCommandResponse($xml);
            }
        }

        if($formatted_response === false) {
            throw new \Exception('Unable to parse namecheap response');
        } else {
            return $formatted_response;
        }
    }

    /**
     * Create instance of self
     *
     * @param string $response
     * @param string $url
     * @param array $post_data
     *
     * @return \Namecheap\Connect\NamecheapResponse
     */
    private static function instantiateSelf($response, $url, $post_data)
    {
        $instance = new self();
        $instance->setResponse($response);
        $instance->setUrl($url);
        $instance->setPostData($post_data);
        return $instance;
    }

    private function setResponse($response)
    {
        $this->response = $response;
    }

    private function setUrl($url)
    {
        $this->url = $url;
    }

    private function setPostData($post_data)
    {
        $this->post_data = $post_data;
    }

    /**
     * Parses error from $response
     *
     * @param array $response
     * @param string $url
     *
     * @return false if unable to parse or array $response
     */
    private function parseErrors($response)
    {
        if(isset($response->Errors->Error)) {
            $error = $response->Errors->Error;
            $attributes = $error->attributes();
            $error_code = (string)$attributes['Number'];
            $error_message = (string)$response->Errors->Error;
            $request_info = self::getRequestInfo($response);
            $formatted_response = array(
                    'status' => 'error',
                    'response' => array(
                            'error_message' => $error_message,
                            'error_code' => $error_code
                        )
                );
            return array_merge($formatted_response, $request_info);
        } else {
            return false;
        }
    }

    /**
     * Parses error from $response
     *
     * @param array $response
     * @param string $url
     *
     * @return false if unable to parse or array $response
     */
    private function parseCommandResponse($response)
    {
        if(isset($response->CommandResponse)) {
            $request_info = self::getRequestInfo($response);
            $formatted_response = array(
                    'status' => 'ok',
                    'response' => (array)$response->CommandResponse
                );
            return array_merge($formatted_response, $request_info);
        } else {
            return false;
        }
    }

    /**
     * Gets request information from the $response
     *
     * @param array $response
     * @param string $url
     *
     * @return array $request_info
     */
    private function getRequestInfo($response)
    {
        $info_keys = array(
                'Server' => 'server',
                'GMTTimeDifference' => 'gmt_time_difference',
                'ExecutionTime' => 'execution_time'
            );

        $request_info = array();
        foreach($info_keys as $key => $new_key) {
            if(isset($response->$key)) {
                $request_info[$new_key] = $response->$key;
            }

            if(is_object($request_info[$new_key])) {
                $request_info[$new_key] = (string)$request_info[$new_key];
            }
        }

        if(empty($request_info)) {
            return array('request_info' => null);
        } else {
            $request_info['url'] = $this->url;
            $request_info['request_data'] = $this->post_data;
            return array('request_info' => $request_info);
        }
    }
}

?>