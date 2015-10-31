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

/**
 * Builds method type object to use
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Namecheap
{
    /**
     * Creates a collection of ImageManager objects for passed image(s)
     *
     * @param string $method_type
     * @param array $config
     *
     * @return instance of a class implementing NamecheapMethodTypesInterface
     */
    public function create($method_type, $config)
    {
        if(class_exists('\\Namecheap\\MethodTypes\\'.$method_type)) {
            $class_string = '\\Namecheap\\MethodTypes\\'.$method_type;
            $class = new $class_string();
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
}

?>