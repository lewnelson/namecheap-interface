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

/**
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionCode 3
     */
    public function testBuildInvalidStatusType()
    {
        $invalid_status = array('invalid_status');
        $valid_request_information = array(
                'any array should be valid'
            );
        $valid_response = array(
                '$response can be array or string'
            );

        $instance = new Response($invalid_status, $valid_request_information, $valid_response);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 6
     */
    public function testBuildInvalidStatusValue()
    {
        $invalid_status = 'invalid_status';
        $valid_request_information = array(
                'any array should be valid'
            );
        $valid_response = array(
                '$response can be array or string'
            );

        $instance = new Response($invalid_status, $valid_request_information, $valid_response);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 4
     */
    public function testBuildInvalidRequestInformationType()
    {
        $valid_status = 'ok';
        $invalid_request_information = 'invalid request_information value';
        $valid_response = array(
                '$response can be array or string'
            );

        $instance = new Response($valid_status, $invalid_request_information, $valid_response);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 5
     */
    public function testBuildInvalidResponseType()
    {
        $valid_status = 'ok';
        $valid_request_information = array(
                'any array should be valid'
            );
        $invalid_response = 'invalid response value';

        $instance = new Response($valid_status, $valid_request_information, $invalid_response);
    }

    private function getObjectWithValidConfig()
    {
        $valid_status = 'ok';
        $valid_request_information = array(
                'custom_key1' => 'any array should be valid'
            );
        $valid_response = array(
                'custom_key2' => '$response can be array or string'
            );

        $instance = new Response($valid_status, $valid_request_information, $valid_response);

        return $instance;
    }

    public function testValidObject()
    {
        $instance = $this->getObjectWithValidConfig();
        $this->assertInstanceOf("\\LewNelson\\Namecheap\\Response", $instance);
    }
}

?>