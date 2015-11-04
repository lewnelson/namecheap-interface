<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LewNelson\Namecheap\Objects\Domains;

use LewNelson\Namecheap\Objects\BaseDomains;

/**
 * Interact with individual domains
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Nameservers extends BaseDomains
{
    /**
     * Sets domain to use default Namecheap DNS servers
     *
     * @return \LewNelson\Namecheap\Response $response
     */
    public function setDefault()
    {
        $command = 'namecheap.domains.dns.setDefault';
        $response = $this->processDefaultRequest($command);
        $response_status = false;

        $attributes = $response['DomainDNSSetDefaultResult']->attributes();
        $response = (string)$attributes['Updated'];
        $response = filter_var($response, FILTER_VALIDATE_BOOLEAN);

        return $this->createResponse($response);
    }

    /**
     * Sets domain to use custom DNS nameservers, if
     * overwrite is set to true this will clear all
     * previous values otherwise it will append/update
     *
     * @param array $nameservers
     * @param array $overwrite
     *
     * @return \LewNelson\Namecheap\Response $response
     */
    public function setCustom($nameservers, $overwrite = true)
    {
        if($overwrite === false) {
            $current_settings = $this->get();
            $current_response = $current_settings->getResponse();
            foreach($current_response['nameservers'] as $current_nameserver) {
                if(!in_array($current_nameserver, $nameservers)) {
                    $nameservers[] = $current_nameserver;
                }
            }
        }

        $parameters = array(
                'Nameservers' => implode(',', $nameservers)
            );
        $command = 'namecheap.domains.dns.setCustom';
        $response = $this->processDefaultRequest($command, $parameters);

        $response_status = false;

        $attributes = $response['DomainDNSSetCustomResult']->attributes();
        $response_status = (string)$attributes['Updated'];
        $response_status = filter_var($response_status, FILTER_VALIDATE_BOOLEAN);

        return $this->createResponse($response_status);
    }

    /**
     * Deletes nameservers from the list. If all nameservers
     * are to be deleted then the setDefault function will be
     * ran rather than having no name servers.
     *
     * @param array $nameservers
     *
     * @return \LewNelson\Namecheap\Response $response
     */
    public function delete($nameservers)
    {
        $current_settings = $this->get();
        $current_response = $current_settings->getResponse();
        foreach($current_response['nameservers'] as $index => $nameserver) {
            if(in_array($nameserver, $nameservers)) {
                if(isset($current_response['nameservers'][$index])) {
                    unset($current_response['nameservers'][$index]);
                }
            }
        }

        if(!empty($current_response['nameservers'])) {
            return $this->setCustom($current_response['nameservers']);
        } else {
            return $this->setDefault();
        }
    }

    /**
     * Gets list of DNS servers associated with domain
     *
     * @return \LewNelson\Namecheap\Response $response
     */
    public function get()
    {
        $command = 'namecheap.domains.dns.getList';
        $response = $this->processDefaultRequest($command);

        $attributes = $response['DomainDNSGetListResult']->attributes();
        $using_namecheap = (string)$attributes['IsUsingOurDNS'];
        $using_namecheap = filter_var($using_namecheap, FILTER_VALIDATE_BOOLEAN);
        $nameservers = (array)$response['DomainDNSGetListResult']->Nameserver;
        $response = array(
                'using_namecheap_dns' => $using_namecheap,
                'nameservers' => $nameservers
            );

        return $this->createResponse($response);
    }
}

?>