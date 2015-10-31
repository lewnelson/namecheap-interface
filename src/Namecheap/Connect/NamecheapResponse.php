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
     * Formats the XML response
     *
     * @param string $response
     * @param string $url
     *
     * @throws \Exception if unable to parse response as a Namecheap response
     *
     * @return array $formatted_response
     */
    public static function format($response, $url)
    {
        $xml = new \SimpleXMLElement($response);
        $attributes = (array)$xml->attributes();
        $formatted_response = false;

        if(isset($attributes['@attributes']['Status'])) {
            $status = $attributes['@attributes']['Status'];
            if($status === 'ERROR') {
                $formatted_response = self::parseErrors($xml, $url);
            } else if($status === 'OK') {
                $formatted_response = self::parseCommandResponse($xml, $url);
            }
        }

        if($formatted_response === false) {
            throw new \Exception('Unable to parse namecheap response');
        } else {
            return $formatted_response;
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
    private static function parseErrors($response, $url)
    {
        $errors = (array)$response->Errors;
        if(isset($errors['Error'])) {
            $request_info = self::getRequestInfo($response, $url);
            $formatted_response = array(
                    'status' => 'error',
                    'response' => $errors['Error']
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
    private static function parseCommandResponse($response, $url)
    {
        if(isset($response->CommandResponse)) {
            $request_info = self::getRequestInfo($response, $url);
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
    private static function getRequestInfo($response, $url)
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
            $request_info['url'] = $url;
            return array('request_info' => $request_info);
        }
    }
}

?>