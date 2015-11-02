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
 * Exception for any errors returned by Namecheap API
 * will use Namecheaps error codes and messages and
 * also attach a full \Namecheap\Response object to
 * $namecheap_response property
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class NamecheapException extends \Exception
{
    /**
     * The error Namecheap response
     *
     * @param \Namecheap\Response $namecheap_response
     */
    private $namecheap_response;

    /**
     * Builds the Namecheap exception
     *
     * @param \Namecheap\Response $response
     */
    public function __construct(\Namecheap\Response $response)
    {
        $this->namecheap_response = $response;
        $response = $response->getResponse();
        $error_message = $response['error_message'];
        $error_code = $response['error_code'];
        parent::__construct($error_message, $error_code);
    }

    /**
     * Gets the failed response
     *
     * @return \Namecheap\Response $namecheap_response
     */
    public function getNamecheapResponse()
    {
        return $this->namecheap_response;
    }
}

?>