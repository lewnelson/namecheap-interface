<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Namecheap\Objects;

use Namecheap\Utils\Utilities;

/**
 * Container of object types
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Container
{
    private $objects;
    private $type;
    private $parameters;

    public function __construct($type, $parameters)
    {
        $this->parameters = $parameters;
        $classes = Utilities::getClasses(__DIR__.'/'.$type);
        foreach($classes as $class) {
            $full_namespace = Utilities::getFullNamespace('Namecheap/Objects/'.$type.'/'.$class);
            $object = new $full_namespace($parameters);
            $name = Utilities::convertCamelCaseToUnderscore($class);
            $this->set($object, $name);
        }
    }

    public function setConnections(\Namecheap\Connect\Connect $connection)
    {
        foreach($this->objects as $index => $object) {
            $object->setConnection($connection);
        }
    }

    public function get($name)
    {
        if(isset($this->objects[$name])) {
            return $this->objects[$name];
        } else {
            return null;
        }
    }

    public function set($object, $name)
    {
        $this->objects[$name] = $object;
    }

    public function getParameter($parameter)
    {
        if(isset($this->parameters[$parameter])) {
            return $this->parameters[$parameter];
        } else {
            return null;
        }
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}

?>