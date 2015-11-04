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
class NamecheapExceptionTest extends \PHPUnit_Framework_TestCase
{
    private $custom_invalid_parameters = array(
            'status' => 'ok',
            'request_information' => array(
                    'request_information'
                ),
            'response' => array(
                    'response' => 'some response',
                    'another_key' => 'another response'
                )
        );

    private $custom_invalid_error_code_parameters = array(
            'status' => 'ok',
            'request_information' => array(
                    'request_information'
                ),
            'response' => array(
                    'error_message' => 'namecheap error message',
                    'error_code' => 'namecheap error code'
                )
        );

    private $custom_valid_parameters = array(
            'status' => 'ok',
            'request_information' => array(
                    'request_information'
                ),
            'response' => array(
                    'error_message' => 'namecheap error message',
                    'error_code' => 100
                )
        );

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 7
     */
    public function testBuildingObjectWithInvalidResponse()
    {
        $status = $this->custom_invalid_parameters['status'];
        $request_information = $this->custom_invalid_parameters['request_information'];
        $response = $this->custom_invalid_parameters['response'];
        $response_object = new \LewNelson\Namecheap\Response($status, $request_information, $response);

        $exception = new NamecheapException($response_object);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 8
     */
    public function testBuildingNamecheapExceptionObjectWithInvalidErrorCodeType()
    {
        $status = $this->custom_invalid_error_code_parameters['status'];
        $request_information = $this->custom_invalid_error_code_parameters['request_information'];
        $response = $this->custom_invalid_error_code_parameters['response'];
        $response_object = new \LewNelson\Namecheap\Response($status, $request_information, $response);

        $exception = new NamecheapException($response_object);
    }

    public function testBuildingNamecheapExceptionObjectWithValidParameters()
    {
        $status = $this->custom_valid_parameters['status'];
        $request_information = $this->custom_valid_parameters['request_information'];
        $response = $this->custom_valid_parameters['response'];
        $response_object = new \LewNelson\Namecheap\Response($status, $request_information, $response);

        $exception = new NamecheapException($response_object);

        $this->assertInstanceOf('\\LewNelson\\Namecheap\\NamecheapException', $exception);
    }

    public function testCheckResponseObjectOnNamecheapException()
    {
        $status = $this->custom_valid_parameters['status'];
        $request_information = $this->custom_valid_parameters['request_information'];
        $response = $this->custom_valid_parameters['response'];
        $response_object = new \LewNelson\Namecheap\Response($status, $request_information, $response);

        $exception = new NamecheapException($response_object);
        $same_response_object = $exception->getNamecheapResponse();

        $this->assertSame($response_object, $same_response_object);
    }
}

?>