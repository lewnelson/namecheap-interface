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
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class NamecheapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Exception
     */
    public function testBuildInvalidConfigType()
    {
        $config = 'Configuration';
        $instance = new Namecheap($config);
    }

    /**
     * @expectedException \Exception
     */
    public function testBuildEmptyConfig()
    {
        $config = array();
        $instance = new Namecheap($config);
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
        $instance = new Namecheap($config);
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

        $instance = new Namecheap($config);
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

        $instance = new Namecheap($config);
    }

    public function testBuildValidConfigArray()
    {
        $config = array(
                'environment' => 'development',
                'api_key' => '7fyhd879hs87hf897dhfds445',
                'api_username' => 'myusername',
                'client_ip' => '112.112.112.112'
            );

        $instance = new Namecheap($config);
        $this->assertInstanceOf("\\Namecheap\\Namecheap", $instance);
    }
}

?>