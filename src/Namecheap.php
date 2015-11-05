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

use LewNelson\Namecheap\Connect\Connect;
use LewNelson\Namecheap\Utils\Utilities;

/**
 * Builds method type object to use
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Namecheap
{
    /**
     * Set up the object with properties to access all
     * method_type objects
     *
     * @param array $config
     * @throws \Exception if $config not array
     */
    public function __construct($config)
    {
        if(!is_array($config)) {
            throw new \Exception('Invalid config, expecting array', 1);
        } else if(empty($config)) {
            throw new \Exception('Cannot pass empty configuration array', 2);
        }

        $prefix = 'LewNelson/Namecheap/MethodTypes';
        $method_types = Utilities::getClasses(__DIR__.'/MethodTypes', $prefix);
        foreach($method_types as $method_type) {
            $object = $this->create($method_type, $config);
            $method_type = str_replace($prefix, '', $method_type);
            $this->setMethodType($object, $method_type);
        }
    }

    /**
     * Builds method type object
     *
     * @param string $method_type where namespacing uses / rather than \
     * @param array $config
     * @throws \Exception if class cannot be found
     *
     * @return instance of a class implementing NamecheapMethodTypesInterface
     */
    private function create($method_type, $config)
    {
        $class_string = $method_type;
        $namespaced_class = Utilities::getFullNamespace($class_string);
        $class = new $namespaced_class();
        $object = $this->build($class, $config);
        return $object;
    }

    /**
     * Boots up instance of NamecheapMethodTypesInterface class by applying namecheap connection
     *
     * @param NamecheapMethodTypesInterface $class
     * @param array $config
     *
     * @return instance of a class implementing NamecheapMethodTypesInterface
     */
    private function build($class, $config)
    {
        $connection = new Connect($config);
        $class->setConnection($connection);
        return $class;
    }

    /**
     * Sets object property for supplied method_type object
     *
     * @param NamecheapMethodTypesInterface $object
     * @param string $method_type
     */
    private function setMethodType(\LewNelson\Namecheap\NamecheapMethodTypesInterface $object, $method_type)
    {
        $method_type = Utilities::convertCamelCaseToUnderscore($method_type);
        $this->$method_type = $object;
    }
}

?>