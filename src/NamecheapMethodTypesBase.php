<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LewNelson\Namecheap;

use LewNelson\Namecheap\Response;
use LewNelson\Namecheap\Objects\Container;

/**
 * Common methods used by classes implementing NamecheapMethodTypesInterface
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class NamecheapMethodTypesBase
{
    /**
     * Namecheap connection object.
     *
     * @param \Namecheap\Connect\Connect $connection
     */
    private $connection;

    /**
     * Last requests info.
     *
     * @param array $request_info
     */
    private $request_info;

    /**
     * Last requests status.
     *
     * @param string $status
     */
    private $status;

    /**
     * Last requests response body.
     *
     * @param mixed string, array $response
     */
    private $response;

    /**
     * Sets the $connection object
     *
     * @param \Namecheap\Connect\Connect $connection
     */
    final public function setConnection(\LewNelson\Namecheap\Connect\Connect $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Gets $connection object
     *
     * @return \Namecheap\Connect\Connect $connection
     */
    final public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Passes request to $connection object
     *
     * @param string $command
     * @param array $parameters
     *
     * @return false on error or array response on success
     */
    final protected function processRequest($command, $parameters = array())
    {
        $response = $this->connection->request($command, $parameters);
        $this->setRequestInfo($response);
        $this->setStatus($response);
        return $response['response'];
    }

    final protected function createResponse($response)
    {
        $this->setResponse($response);
        $response = new Response($this->getStatus(), $this->getRequestInfo(), $this->getResponse());
        return $response;
    }

    /**
     * Sets $request_info from a full $response array
     *
     * @param array $response
     */
    final private function setRequestInfo($response)
    {
        $this->request_info = $response['request_info'];
    }

    /**
     * Sets $status from a full $response array
     *
     * @param array $response
     */
    final private function setStatus($response)
    {
        $this->status = $response['status'];
    }

    /**
     * Sets $response property
     *
     * @param mixed $response
     */
    final protected function setResponse($response)
    {

        $this->response = $response;
    }

    /**
     * Gets $request_info
     *
     * @return array $request_info or null if not set
     */
    final public function getRequestInfo()
    {
        if(isset($this->request_info)) {
            return $this->request_info;
        } else {
            return null;
        }
    }

    /**
     * Gets $status
     *
     * @return string $status or null if not set
     */
    final public function getStatus()
    {
        if(isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    /**
     * Gets $response
     *
     * @return $response or null if not set
     */
    final public function getResponse()
    {
        if(isset($this->response)) {
            return $this->response;
        } else {
            return null;
        }
    }

    /**
     * Gets Container of objects by type
     *
     * @param string $type
     * @param array $config
     *
     * @return \Namecheap\Objects\Container $container
     */
    final protected function getContainer($type, $config)
    {
        $connection = $this->getConnection();
        $container = new Container('Domains', $connection, $config);
        return $container;
    }
}

?>