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

use Namecheap\Objects\BaseObject;

/**
 * Interact with individual domains
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class BaseDomains extends BaseObject
{
    /**
     * Build the object with supplied parameters
     *
     * @param array $domain_parameters
     *
     * @throws \Exception if missing name parameter
     * @throws \Exception if unable to parse top level domain and sub level domain
     */
    public function __construct($domain_parameters)
    {
        parent::__construct($domain_parameters);

        $domain = $this->getParameter('name');
        if($domain === null) {
            throw new \Exception('Missing parameter `name` from `domain_parameters`');
        }
    }

    /**
     * Intermediatery function to set common parameters for all domain
     * requests, then runs parent function
     *
     * @param string $command
     * @param array $parameters
     *
     * @return array $response
     */
    protected function processDefaultRequest($command, $parameters = array())
    {
        $default_parameters = array(
                'SLD' => $this->getParameter('sld'),
                'TLD' => $this->getParameter('tld')
            );

        $parameters = array_merge($default_parameters, $parameters);
        return $this->processRequest($command, $parameters);
    }
}

?>