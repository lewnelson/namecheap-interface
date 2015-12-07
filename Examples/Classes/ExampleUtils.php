<?php

namespace Examples\Classes;

use LewNelson\Namecheap\Client;

/**
 * Common functions used across all Example objects
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class ExampleUtils
{
    /**
     * Builds config array from configuration.json file.
     * If you downloaded the source code you will need to rename
     * and configure the configuration.json.example file
     *
     * @return array
     */
    private static function getConfig()
    {
        $config_file_path = preg_replace('/Examples\/Classes$/', 'configuration.json', __DIR__);
        if(!file_exists($config_file_path)) {
            throw new \Exception('Missing configuration.json file');
        }

        $config = file_get_contents($config_file_path);
        $config_array = json_decode($config, true);
        if($config_array === null) {
            throw new \Exception('configuration.json contains invalid JSON');
        }

        return $config_array;
    }

    /**
     * Instantiates Namecheap client
     *
     * @return \LewNelson\Namecheap\Client
     */
    public static function getClient()
    {
        $config = self::getConfig();
        $client = new Client($config);
        return $client;
    }
}

?>