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

/**
 * Interact with group domains methods
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class ManageDomains extends NamecheapMethodTypesBase implements NamecheapMethodTypesInterface
{
    /**
     * Gets list of domains attached to account and builds domain objects
     * for each one. Or returns empty array for no domains.
     *
     * @param array $request_parameters (optional)
     *
     * @return \LewNelson\Namecheap\Response $response
     */
    public function getDomains($request_parameters = array())
    {
        $valid_parameters = array(
                'list_type' => 'ListType',
                'search_term' => 'SearchTerm',
                'page' => 'Page',
                'num_domains' => 'PageSize',
                'sort_by' => 'SortBy'
            );

        $new_request_parameters = array();
        foreach($request_parameters as $key => $value) {
            if(!isset($valid_parameters[$key])) {
                throw new \Exception('Invalid parameter `'.$key.'` used for getDomains()');
            } else {
                $new_request_parameters[$valid_parameters[$key]] = $value;
            }
        }

        $domains = array();
        $command = 'namecheap.domains.getList';
        $response = $this->processRequest($command, $new_request_parameters);

        if($response->getStatus() === 'ok') {
            foreach($response['DomainGetListResult']->Domain as $index => $domain) {
                $attributes = $domain->attributes();
                $domain_object = $this->buildObject($attributes);
                $domains[] = $domain_object;
            }

            $paging = (array)$response['Paging'];
            $pagination['total_domains'] = (int)$paging['TotalItems'];
            $pagination['current_page'] = (int)$paging['CurrentPage'];
            $pagination['num_items_on_page'] = (int)$paging['PageSize'];
            $pagination['total_pages'] = (int)ceil($pagination['total_domains'] / $pagination['num_items_on_page']);
            $response = array(
                    'pagination' => $pagination,
                    'domains' => $domains
                );
        }

        return $this->createResponse($response);
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
     * @return \LewNelson\Namecheap\Response $response
     */
    public function getDomain($domain_name)
    {
        $request_parameters = array(
                'SearchTerm' => $domain_name
            );
        $domain_object = null;
        $command = 'namecheap.domains.getList';
        $response = $this->processRequest($command, $request_parameters);

        if($response->getStatus() === 'ok') {
            foreach($response['DomainGetListResult']->Domain as $index => $domain) {
                $attributes = $domain->attributes();
                if((string)$attributes['Name'] === $domain_name) {
                    $response = $this->buildObject($attributes);
                    break;
                }
            }
        }

        return $this->createResponse($response);
    }

    /**
     * Parses the domain XML array to domain object used for getList
     *
     * @param string $domain
     *
     * @return \LewNelson\Namecheap\Objects\Domains $domain_object
     */
    private function buildObject($domain)
    {
        $domain_parameters = array(
                'id' => (string)$domain['ID'],
                'name' => (string)$domain['Name'],
                'username' => (string)$domain['User'],
                'created' => (string)$domain['Created'],
                'expires' => (string)$domain['Expires'],
                'whois_guard' => (string)$domain['WhoisGuard']
            );

        $boolean_keys = array(
                'IsExpired' => 'expired',
                'IsLocked' => 'locked',
                'AutoRenew' => 'auto_renew'
            );

        foreach($boolean_keys as $key => $new_key) {
            if((string)$domain[$key] === 'false') {
                $domain_parameters[$new_key] = false;
            } else if((string)$domain[$key] === 'true') {
                $domain_parameters[$new_key] = true;
            }
        }

        $container = $this->getContainer('Domains', $domain_parameters);
        return $container;
    }
}

?>