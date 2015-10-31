<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Namecheap\MethodTypes;

use Namecheap\NamecheapMethodTypesBase;
use Namecheap\NamecheapMethodTypesInterface;
use Namecheap\Objects\Domains as DomainObject;

/**
 * Interact with group domains methods
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Domains extends NamecheapMethodTypesBase implements NamecheapMethodTypesInterface
{
    /**
     * Gets list of domains attached to account and builds domain objects
     * for each one. Or returns empty array for no domains.
     *
     * @param array $request_parameters (optional)
     *
     * @return \Namecheap\Response $response
     */
    public function getList($request_parameters = array())
    {
        $domains = array();
        $command = 'namecheap.domains.getList';
        $response = $this->processRequest($command, $request_parameters);
        $status = $this->getStatus();
        if($status !== 'ok') {
            return $this->createResponse($response);
        }

        foreach($response['DomainGetListResult']->Domain as $index => $domain) {
            $domain = (array)$domain;
            $domain_object = $this->parseMultipleDomainResponse($domain);
            $domains[] = $domain_object;
        }

        return $this->createResponse($domains);
    }

    /**
     * Gets single domain object for specified domain
     * Does not use namecheaps getInfo command instead will
     * get list of all domains and return single object.
     * This is due to large differences in Namecheaps
     * response for getList compared to getInfo. Also
     * getInfo seems to be really slow.
     *
     * @param string $domain_name
     *
     * @return \Namecheap\Response $response
     */
    public function getDomain($domain_name)
    {
        $response = null;
        $domains = $this->getList();
        if($domains->getStatus() === 'ok') {
            foreach($domains->getResponse() as $index => $domain_object) {
                if($domain_name === $domain_object->getParameter('name')) {
                    $response = $domain_object;
                    break;
                }
            }

            return $this->createResponse($response);
        } else {
            return $domains;
        }
    }

    /**
     * Parses the domain XML array to domain object used for getList
     *
     * @param string $domain
     *
     * @return \Namecheap\Objects\Domains $domain_object
     */
    private function parseMultipleDomainResponse($domain)
    {
        $connection = $this->getConnection();
        $parameters = $domain['@attributes'];
        $domain_parameters = array(
                'id' => $parameters['ID'],
                'name' => $parameters['Name'],
                'username' => $parameters['User'],
                'created' => $parameters['Created'],
                'expires' => $parameters['Expires'],
                'whois_guard' => $parameters['WhoisGuard']
            );

        $boolean_keys = array(
                'IsExpired' => 'expired',
                'IsLocked' => 'locked',
                'AutoRenew' => 'auto_renew'
            );

        foreach($boolean_keys as $key => $new_key) {
            if($parameters[$key] === 'false') {
                $domain_parameters[$new_key] = false;
            } else if($parameters[$key] === 'true') {
                $domain_parameters[$new_key] = true;
            }
        }

        $domain_object = new DomainObject($domain_parameters);
        $domain_object->setConnection($connection);
        return $domain_object;
    }
}

?>