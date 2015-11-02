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

/**
 * Interact with individual domains
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
interface DomainsObjectsInterface
{
    public function get();

    public function create($config);

    public function update($identifier, $config);

    public function delete($identifier);
}