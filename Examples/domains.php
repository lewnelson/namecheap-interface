<?php

include_once(getcwd().'/../vendor/autoload.php');

$config = array(
        // CHANGE ME
        'api_key' => 'your_namecheap_api_key',
        'api_username' => 'your_namecheap_username',
        'environment' => 'development',     // Set to production to use live namecheap endpoint, otherwise development uses Namecheaps sandbox environment which requires a separate sandbox account
        'client_ip' => '0.0.0.0'    // Should be set to your public IP. Namecheap also requires this IP to be  whitelisted
    );

try {
    $namecheap = new \Namecheap\Namecheap();
    $instance = $namecheap->create('Domains', $config);
    $domains = $instance->getList();
    $domain = $instance->getDomain('lewnelson.io');
    $dns_servers = array(
            'dns.server.com',
            'dns2.server.com'
        );

    $dns_servers_a = array(
            'dns3.server.com'
        );

    $emails = array(
            array(
                    'mailbox' => 'subdomain1',
                    'forwarding_email' => 'lewis@lewnelson.com'
                ),
            array(
                    'mailbox' => 'subdomain2',
                    'forwarding_email' => 'lewnelson1991@gmail.com'
                ),
            array(
                    'mailbox' => 'another',
                    'forwarding_email' => 'someone@email.com'
                ),
            array(
                    'mailbox' => 'another2',
                    'forwarding_email' => 'someone@email.com'
                )
        );

    $remove_emails = array(
            'someone@email.com'
        );

    $remove_mailboxes = array(
            'subdomain2'
        );

    $new_hosts = array(
            array(
                    'type' => 'CNAME',
                    'address' => 'somewhere.ontheinternet.com',
                    'host_name' => 'overhere'
                )
        );


    //var_dump($domain->getResponse()->setDefault());
    //var_dump($domain->getResponse()->setCustom($dns_servers_a, false));
    //var_dump($domain->getResponse()->removeNameServers($dns_servers_a));
    //var_dump($domain->getResponse()->getList());
    //var_dump($domain->getResponse()->setEmailForwarding($emails));
    //var_dump($domain->getResponse()->getEmailForwarding());
    //var_dump($domain->getResponse()->removeEmailForwarding($remove_mailboxes, $remove_emails));

    //var_dump($domain->getResponse()->setHostRecords($new_hosts));
    var_dump($domain->getResponse()->getHostRecords());
    var_dump($domain->getResponse()->getMxRecords());
    //var_dump($domain->getResponse()->deleteHostRecords(array('subdomain')));
} catch(\Exception $exception) {
    var_dump($exception->getMessage());
}

?>