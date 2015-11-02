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
use Namecheap\Utils\Utilities;

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
     */
    public function __construct($config)
    {
        $method_types = Utilities::getClasses(__DIR__.'/MethodTypes');
        foreach($method_types as $method_type) {
            $object = $this->create($method_type, $config);
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
        $class_string = 'Namecheap/MethodTypes/'.$method_type;
        $namespaced_class = Utilities::getFullNamespace($class_string);
        if(class_exists($namespaced_class)) {
            $class = new $namespaced_class();
            $object = $this->build($class, $config);
            return $object;
        } else {
            throw new \Exception('No class found for `'.$method_type.'`');
        }
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
    private function setMethodType(\Namecheap\NamecheapMethodTypesInterface $object, $method_type)
    {
        $method_type = Utilities::convertCamelCaseToUnderscore($method_type);
        $this->$method_type = $object;
    }
}

?>