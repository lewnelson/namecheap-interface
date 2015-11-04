<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LewNelson\Namecheap\Objects;

use LewNelson\Namecheap\Utils\Utilities;

/**
 * Container of object types
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Container
{
    /**
     * Holds all objects
     *
     * @param array $objects
     */
    private $objects;

    /**
     * Defines type of objects to use
     *
     * @param string $type
     */
    private $type;

    /**
     * Configuration used for building each object
     *
     * @param array $configuration
     */
    private $configuration;

    /**
     * Gets all classes based on passed type, instantiates
     * them and stores them under $objects using
     * underscored keys
     *
     * @param string $type
     * @param \LewNelson\Namecheap\Connect\Connect $connection
     * @param array $configuration
     */
    public function __construct($type, $connection, $configuration)
    {
        $classes = Utilities::getClasses(__DIR__.'/'.$type);
        foreach($classes as $class) {
            $full_namespace = Utilities::getFullNamespace('LewNelson/Namecheap/Objects/'.$type.'/'.$class);
            $object = new $full_namespace($configuration);
            $name = Utilities::convertCamelCaseToUnderscore($class);
            $this->set($object, $name);
        }

        $this->setConnections($connection);
    }

    /**
     * Set connection property on each object
     *
     * @param \LewNelson\Namecheap\Connect\Connect $connection
     */
    private function setConnections(\LewNelson\Namecheap\Connect\Connect $connection)
    {
        foreach($this->objects as $index => $object) {
            $object->setConnection($connection);
        }
    }

    /**
     * Get an object based on key name
     *
     * @param string $name
     *
     * @return \Namecheap\NamecheapObjectInterface or null
     */
    public function get($name)
    {
        if(isset($this->objects[$name])) {
            return $this->objects[$name];
        } else {
            return null;
        }
    }

    /**
     * Sets object under key name
     *
     * @param \LewNelson\Namecheap\NamecheapObjectInterface $object
     * @param string $name
     */
    private function set(\LewNelson\Namecheap\NamecheapObjectInterface $object, $name)
    {
        $this->objects[$name] = $object;
    }
}

?>