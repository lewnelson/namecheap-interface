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

use Namecheap\Connect\Connect;

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
        if(!is_string($status)) {
            throw new \Exception('Invalid value provided for `status` expecting string');
        } else {
            $this->status = $status;
        }
    }

    /**
     * Sets request_information property
     *
     * @param array or null $request_information
     */
    private function setRequestInformation($request_information)
    {
        $this->request_information = $request_information;
    }

    /**
     * Sets response property
     *
     * @param array, string or null $response
     */
    private function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Gets status property
     *
     * @param string $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets request_information property
     *
     * @param mixed array, null $request_information
     */
    public function getRequestInformation()
    {
        return $this->request_information;
    }

    /**
     * Gets response property
     *
     * @param mixed array, string, null $response
     */
    public function getResponse()
    {
        return $this->response;
    }
}

?>