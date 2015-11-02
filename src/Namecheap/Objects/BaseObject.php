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

use Namecheap\NamecheapMethodTypesBase;
use Namecheap\NamecheapObjectInterface;

/**
 * Common methods used by Object classes
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class BaseObject extends NamecheapMethodTypesBase implements NamecheapObjectInterface
{
    /**
     * key, value properties of object.
     *
     * @param array $parameters
     */
    private $parameters;

    /**
     * Builds object
     *
     * @param array $parameters
     */
    public function __construct($parameters)
    {
        $this->setParameters($parameters);
    }

    /**
     * Sets $parameters property
     *
     * @param array $parameters
     */
    private function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets $parameters[$key] overwrites previous values
     *
     * @param string $key
     * @param mixed $value
     */
    final public function setParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Gets $parameters property
     *
     * @return array $parameters
     */
    final public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Gets $parameters[$key] or null
     *
     * @param string $key
     *
     * @return mixed value of $parameters[$key] or null if $key is not set
     */
    final public function getParameter($key)
    {
        if(isset($this->parameters[$key])) {
            return $this->parameters[$key];
        } else {
            return null;
        }
    }
}

?>