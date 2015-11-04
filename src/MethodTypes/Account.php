<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LewNelson\Namecheap\MethodTypes;

use LewNelson\Namecheap\NamecheapMethodTypesBase;
use LewNelson\Namecheap\NamecheapMethodTypesInterface;
use LewNelson\Namecheap\Objects\Container;

/**
 * Interact with group domains methods
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Account extends NamecheapMethodTypesBase implements NamecheapMethodTypesInterface
{
    /**
     *  Get list of objects
     *
     *  @param array $parameters
     */
    public function getList($parameters = array())
    {

    }

    /**
     *  Gets single object
     *
     *  @param string $identifier
     *  @param array $parameters
     */
    public function getObject($identifier, $parameters = array())
    {

    }

    /**
     *  Builds object
     *
     *  @param array $response_array
     */
    private function buildObject($response_array)
    {
        
    }
}

?>