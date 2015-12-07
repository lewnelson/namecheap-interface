<?php

namespace Examples\Classes;

use Examples\Classes\BaseExample;

/**
 * Class demonstrates some examples from the ManageDomains functionality
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class ManageDomainsExamples extends BaseExample
{
    public function getMyDomains($page = 1)
    {
        $page = (int)$page;
        $params = array(
                'Page' => $page
            );

        $client = $this->getClient();
        $my_domains = $client->manage_domains
                                ->getDomains($params);

        $response = array(
                'status' => $my_domains->getStatus()
            );
        if($my_domains->getStatus() === 'ok') {
            $response['pagination'] = $my_domains->getResponse()['pagination'];
            foreach($my_domains->getResponse()['domains'] as $index => $domain) {
                $domain_parameters = $domain->getConfig();
                $response['domains'][] = $domain_parameters;
            }
        } else {
            $response['error'] = $my_domains->getResponse();
        }

        return $response;
    }
}

?>