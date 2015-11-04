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
 * Define methods for objects
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
interface NamecheapObjectInterface
{
    /**
     * Sets $parameters[$key] overwrites previous values
     *
     * @param string $key
     * @param mixed $value
     */
    public function setParameter($key, $value);

    /**
     * Gets $parameters property
     */
    public function getParameters();

    /**
     * Gets $parameters[$key] or null
     *
     * @param string $key
     */
    public function getParameter($key);
}

?>