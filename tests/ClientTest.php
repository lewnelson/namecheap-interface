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
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testBuildInvalidConfigType()
    {
        $config = 'Configuration';
        $instance = new Client($config);
    }

    /**
     * @expectedException \Exception
     */
    public function testBuildEmptyConfig()
    {
        $config = array();
        $instance = new Client($config);
    }

    /**
     * @expectedException \Exception
     */
    public function testBuildInvalidConfigValues()
    {
        $config = array(
                'notavalidconfig',
                'invalid',
                'shouldn\'t work'
            );
        $instance = new Client($config);
    }

    /**
     * @expectedException \Exception
     */
    public function testBuildInvalidIpv4Address()
    {
        $config = array(
                'environment' => 'development',
                'api_key' => '7fyhd879hs87hf897dhfds445',
                'api_username' => 'myusername',
                'client_ip' => '256.1.1.5'
            );

        $instance = new Client($config);
    }

    /**
     * @expectedException \Exception
     */
    public function testBuildInvalidEnvironment()
    {
        $config = array(
                'environment' => 'undefined',
                'api_key' => '7fyhd879hs87hf897dhfds445',
                'api_username' => 'myusername',
                'client_ip' => '112.112.112.112'
            );

        $instance = new Client($config);
    }

    public function testBuildValidConfigArray()
    {
        $config = array(
                'environment' => 'development',
                'api_key' => '7fyhd879hs87hf897dhfds445',
                'api_username' => 'myusername',
                'client_ip' => '112.112.112.112'
            );

        $instance = new Client($config);
        $this->assertInstanceOf("\\LewNelson\\Namecheap\\Client", $instance);
    }
}

?>