<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Namecheap;

/**
 * Response object
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Response
{
    /**
     * Requests status.
     *
     * @param string $status
     */
    private $status;

    /**
     * Requests info.
     *
     * @param array $request_info
     */
    private $request_information;

    /**
     * Requests response body.
     *
     * @param mixed string, array $response
     */
    private $response;

    /**
     * Builds the response object
     *
     * @param string $status
     * @param array $request_information
     * @param mixed string, array $response
     */
    public function __construct($status, $request_information, $response)
    {
        $this->setStatus($status);
        $this->setRequestInformation($request_information);
        $this->setResponse($response);
    }

    /**
     * Sets status property
     *
     * @param string $status
     *
     * @throws \Exception if status is not a string
     */
    private function setStatus($status)
    {
        $valid_statuses = array(
                'error',
                'ok'
            );

        if(!is_string($status)) {
            throw new \Exception('Invalid value provided for `status` expecting string', 3);
        } else {
            if(!in_array($status, $valid_statuses)) {
                throw new \Exception('Invalid value provided for `status`', 6);
            } else {
                $this->status = $status;
            }
        }
    }

    /**
     * Sets request_information property
     *
     * @param array $request_information
     */
    private function setRequestInformation($request_information)
    {
        if(!is_array($request_information)) {
            throw new \Exception('Invalid value provided for `request_information` expecting array', 4);
        } else {
            $this->request_information = $request_information;
        }
    }

    /**
     * Sets response property
     *
     * @param array $response
     */
    private function setResponse($response)
    {
        if(!is_array($response)) {
            throw new \Exception('Invalid value provided for `response` expecting array', 5);
        } else {
            $this->response = $response;
        }
    }

    /**
     * Gets status property
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets request_information property
     *
     * @return array $request_information
     */
    public function getRequestInformation()
    {
        return $this->request_information;
    }

    /**
     * Gets response property
     *
     * @return array $response
     */
    public function getResponse()
    {
        return $this->response;
    }
}

?>