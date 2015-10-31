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

use Namecheap\BaseObject;

/**
 * Interact with individual domains
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Domains extends BaseObject
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

        preg_match("/^(?'sld'[^\.]+)\.(?'tld'.*)$/", $domain, $levels);
        if(isset($levels['sld']) && isset($levels['tld'])) {
            $this->setParameter('sld', $levels['sld']);
            $this->setParameter('tld', $levels['tld']);
        } else {
            throw new \Exception('Unable to retrieve TLD and SLD from domain name `'.$domain.'`');
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
    private function processDefaultRequest($command, $parameters = array())
    {
        $default_parameters = array(
                'SLD' => $this->getParameter('sld'),
                'TLD' => $this->getParameter('tld')
            );

        $parameters = array_merge($default_parameters, $parameters);
        return $this->processRequest($command, $parameters);
    }

    /**
     * Sets domain to use default Namecheap DNS servers
     *
     * @return \Namecheap\Response $response
     */
    public function setDefault()
    {
        $command = 'namecheap.domains.dns.setDefault';
        $response = $this->processDefaultRequest($command);
        $response_status = false;
        if($this->getStatus() === 'ok') {
            $attributes = $response['DomainDNSSetDefaultResult']->attributes();
            $response_status = (string)$attributes['Updated'];
            $response_status = filter_var($response_status, FILTER_VALIDATE_BOOLEAN);
        }

        return $this->createResponse($response_status);
    }

    /**
     * Sets domain to use custom DNS nameservers, if
     * overwrite is set to true this will clear all
     * previous values otherwise it will append/update
     *
     * @param array $nameservers
     * @param array $overwrite
     *
     * @return \Namecheap\Response $response
     */
    public function setCustom($nameservers, $overwrite = true)
    {
        if($overwrite === false) {
            $current_settings = $this->getList();
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
        if($this->getStatus() === 'ok') {
            $attributes = $response['DomainDNSSetCustomResult']->attributes();
            $response_status = (string)$attributes['Updated'];
            $response_status = filter_var($response_status, FILTER_VALIDATE_BOOLEAN);
        }

        return $this->createResponse($response_status);
    }

    /**
     * Deletes nameservers from the list. If all nameservers
     * are to be deleted then the setDefault function will be
     * ran rather than having no name servers.
     *
     * @param array $nameservers
     *
     * @return \Namecheap\Response $response
     */
    public function removeNameServers($nameservers)
    {
        $current_settings = $this->getList();
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
     * @return \Namecheap\Response $response
     */
    public function getList()
    {
        $command = 'namecheap.domains.dns.getList';
        $response = $this->processDefaultRequest($command);
        if($this->getStatus() === 'ok') {
            $attributes = $response['DomainDNSGetListResult']->attributes();
            $using_namecheap = (string)$attributes['IsUsingOurDNS'];
            $using_namecheap = filter_var($using_namecheap, FILTER_VALIDATE_BOOLEAN);
            $nameservers = (array)$response['DomainDNSGetListResult']->Nameserver;
            $response = array(
                    'using_namecheap_dns' => $using_namecheap,
                    'nameservers' => $nameservers
                );
        }
        return $this->createResponse($response);
    }

    /**
     * Gets hosts for domain
     *
     * @return \Namecheap\Response $response
     */
    private function getHosts()
    {
        $command = 'namecheap.domains.dns.getHosts';
        $response = $this->processDefaultRequest($command);
        $response_hosts = array();
        if($this->getStatus() === 'ok') {
            $count = $response['DomainDNSGetHostsResult']->host->count();
            for($i = 0; $i < $count; $i++) {
                $host = $response['DomainDNSGetHostsResult']->host[$i];
                $parameters = $host->attributes();
                $host_params = array(
                        'id' => (string)$parameters['HostId'],
                        'type' => (string)$parameters['Type'],
                        'address' => (string)$parameters['Address'],
                        'mx_pref' => (string)$parameters['MXPref'],
                        'ttl' => (string)$parameters['TTL'],
                        'associated_app_title' => (string)$parameters['AssociatedAppTitle'],
                        'friendly_name' => (string)$parameters['FriendlyName'],
                        'active' => filter_var((string)$parameters['IsActive'], FILTER_VALIDATE_BOOLEAN),
                        'ddns_enabled' => filter_var((string)$parameters['IsDDNSEnabled'], FILTER_VALIDATE_BOOLEAN),
                        'host_name' => (string)$parameters['Name']
                    );

                $response_hosts[] = $host_params;
            }

            $response = $response_hosts;
        }

        return $this->createResponse($response);
    }

    /**
     * Gets email forwarding for domain
     *
     * @return \Namecheap\Response $response
     */
    public function getEmailForwarding()
    {
        $parameters = array(
                'DomainName' => $this->getParameter('name')
            );
        $command = 'namecheap.domains.dns.getEmailForwarding';
        $response = $this->processRequest($command, $parameters);
        $forwarding_response = array();
        if($this->getStatus() === 'ok') {
            $forwarding = $response['DomainDNSGetEmailForwardingResult']->Forward;
            $total = $forwarding->count();
            for($i = 0; $i < $total; $i++) {
                $attributes = $forwarding[$i]->attributes();
                $mailbox = (string)$attributes['mailbox'];
                $email = (string)$forwarding[$i];
                $forwarding_response[] = array(
                        'forwarding_email' => $email,
                        'mailbox' => $mailbox
                    );
            }
        }

        return $this->createResponse($forwarding_response);
    }

    /**
     * Sets hosts for domain. This does not
     * delete hosts it will only update and
     * add hosts. Used for setMxRecords and
     * setHostRecords.
     *
     * @param array $hosts
     * @param array $current_subdomains
     * @param array $overwritable_hosts
     * @param array $additonal_hosts
     *
     * @return \Namecheap\Response $response
     */
    private function setHosts($hosts, $current_subdomains, $overwritable_hosts, $additonal_hosts)
    {
        foreach($hosts as $parameters) {
            if(isset($current_subdomains[$parameters['host_name']])) {
                $index = $current_subdomains[$parameters['host_name']];
                $overwritable_hosts[$index] = $parameters;
            } else {
                $overwritable_hosts[] = $parameters;
            }
        }

        $overwritable_hosts = array_merge($overwritable_hosts, $additonal_hosts);
        $new_parameters = array();
        $i = 0;
        foreach($overwritable_hosts as $parameters) {
            $new_parameters = array_merge($new_parameters, $this->buildHostsConfig($parameters, $i));
            $i = $i + 1;
        }

        $command = 'namecheap.domains.dns.setHosts';
        $response = $this->processDefaultRequest($command, $new_parameters);
        if($this->getStatus() === 'ok') {
            $attributes = $response['DomainDNSSetHostsResult']->attributes();
            $response = filter_var((string)$attributes['IsSuccess'], FILTER_VALIDATE_BOOLEAN);
        }
        return $this->createResponse($response);
    }

    /**
     * Write all host records, will clear all
     * previous records then write new ones
     *
     * @param array $hosts
     *
     * @return \Namecheap\Response $response
     */
    private function setHostsOverwrite($hosts)
    {
        $new_parameters = array();
        $i = 0;
        foreach($hosts as $parameters) {
            $new_parameters = array_merge($new_parameters, $this->buildHostsConfig($parameters, $i));
            $i = $i + 1;
        }

        $command = 'namecheap.domains.dns.setHosts';
        $response = $this->processDefaultRequest($command, $new_parameters);
        if($this->getStatus() === 'ok') {
            $attributes = $response['DomainDNSSetHostsResult']->attributes();
            $response = filter_var((string)$attributes['IsSuccess'], FILTER_VALIDATE_BOOLEAN);
        }
        return $this->createResponse($response);
    }

    /**
     * Sets MX records, updates or adds new
     * MX records. Other host records remain
     * unaffected.
     *
     * @param array $hosts
     * @throws \Exception if invalid type is passed
     *
     * @return \Namecheap\Response $response
     */
    public function setMxRecords($hosts)
    {
        foreach($hosts as $index => $host) {
            if($host['type'] !== 'MX' && $host['type'] !== 'MXE') {
                throw new \Exception('Cannot only set hosts with type MX or MXE. Use setHostRecords() instead.');
            }
        }

        $all_hosts = $this->getHosts();
        $all_hosts = $all_hosts->getResponse();
        $current_mx_records = $this->getMxRecords($all_hosts);
        $current_mx_records = $current_mx_records->getResponse();
        $current_host_records = $this->getHostRecords($all_hosts);
        $current_host_records = $current_host_records->getResponse();

        $current_subdomains = array();
        foreach($current_mx_records as $index => $params) {
            $subdomain = $params['host_name'];
            $current_subdomains[$subdomain] = $index;
        }

        return $this->setHosts($hosts, $current_subdomains, $current_mx_records, $current_host_records);
    }

    /**
     * Sets host records, updates or adds new
     * host records. MX records remain
     * unaffected.
     *
     * @param array $hosts
     * @throws \Exception if invalid type is passed
     *
     * @return \Namecheap\Response $response
     */
    public function setHostRecords($hosts)
    {
        foreach($hosts as $index => $host) {
            if($host['type'] === 'MX' || $host['type'] === 'MXE') {
                throw new \Exception('Cannot set new hosts with type MX or MXE. Use setMxRecords() instead.');
            }
        }

        $all_hosts = $this->getHosts();
        $all_hosts = $all_hosts->getResponse();
        $current_mx_records = $this->getMxRecords($all_hosts);
        $current_mx_records = $current_mx_records->getResponse();
        $current_host_records = $this->getHostRecords($all_hosts);
        $current_host_records = $current_host_records->getResponse();

        $current_subdomains = array();
        foreach($current_host_records as $index => $params) {
            $subdomain = $params['host_name'];
            $current_subdomains[$subdomain] = $index;
        }

        return $this->setHosts($hosts, $current_subdomains, $current_host_records, $current_mx_records);
    }

    /**
     * Returns all MX and MXE records
     *
     * @param array $all_hosts (optional) used from other functions in class
     *
     * @return \Namecheap\Response $response
     */
    public function getMxRecords($all_hosts = false)
    {
        if($all_hosts === false) {
            $all_hosts = $this->getHosts();
            $all_hosts = $all_hosts->getResponse();
        }

        $mx_hosts = array();
        foreach($all_hosts as $index => $host) {
            $type = $host['type'];
            if($type === 'MX' || $type === 'MXE') {
                $mx_hosts[] = $host;
            }
        }

        return $this->createResponse($mx_hosts);
    }

    /**
     * Returns all host records minus MX and MXE records
     *
     * @param array $all_hosts (optional) used from other functions in class
     *
     * @return \Namecheap\Response $response
     */
    public function getHostRecords($all_hosts = false)
    {
        if($all_hosts === false) {
            $all_hosts = $this->getHosts();
            $all_hosts = $all_hosts->getResponse();
        }

        $host_records = array();
        foreach($all_hosts as $index => $host) {
            $type = $host['type'];
            if($type !== 'MX' && $type !== 'MXE') {
                $host_records[] = $host;
            }
        }

        return $this->createResponse($host_records);
    }

    /**
     * Deletes MX records base on subdomains passed in
     * array $hosts
     *
     * @param array $hosts
     *
     * @return \Namecheap\Response $response
     */
    public function deleteMxHosts($hosts)
    {
        $all_hosts = $this->getHosts();
        $all_hosts = $all_hosts->getResponse();
        $current_mx_records = $this->getMxRecords($all_hosts);
        $current_mx_records = $current_mx_records->getResponse();
        $current_host_records = $this->getHostRecords($all_hosts);
        $current_host_records = $current_host_records->getResponse();

        foreach($current_mx_records as $index => $host) {
            if(in_array($host['host_name'], $hosts)) {
                if(isset($current_mx_records[$index])) {
                    unset($current_mx_records[$index]);
                }
            }
        }

        $current_mx_records = array_values($current_mx_records);
        $combined_hosts = array_merge($current_mx_records, $current_host_records);
        return $this->setHostsOverwrite($combined_hosts);
    }

    /**
     * Deletes host records base on subdomains passed in
     * array $hosts
     *
     * @param array $hosts
     *
     * @return \Namecheap\Response $response
     */
    public function deleteHostRecords($hosts)
    {
        $all_hosts = $this->getHosts();
        $all_hosts = $all_hosts->getResponse();
        $current_mx_records = $this->getMxRecords($all_hosts);
        $current_mx_records = $current_mx_records->getResponse();
        $current_host_records = $this->getHostRecords($all_hosts);
        $current_host_records = $current_host_records->getResponse();

        foreach($current_host_records as $index => $host) {
            if(in_array($host['host_name'], $hosts)) {
                if(isset($current_host_records[$index])) {
                    unset($current_host_records[$index]);
                }
            }
        }

        $current_host_records = array_values($current_host_records);
        $combined_hosts = array_merge($current_host_records, $current_mx_records);
        return $this->setHostsOverwrite($combined_hosts);
    }

    /**
     * Build the parameters for setting hosts
     *
     * @param array $parameters
     * @param integer $index
     * @param string $type
     *
     * @throws \Exception when missing required parameters
     *
     * @return array $new_parameters
     */
    private function buildHostsConfig($parameters, $index)
    {
        $required_parameters = array(
                'host_name' => 'HostName',
                'type' => 'RecordType',
                'address' => 'Address'
            );

        $optional_parameters = array(
                'email_type' => 'EmailType',
                'ttl' => 'TTL'
            );

        $new_parameters = array();

        foreach($required_parameters as $key => $new_key) {
            if(!isset($parameters[$key])) {
                throw new \Exception('Missing parameters for updating hosts');
            } else {
                $new_parameters[$new_key.$index] = $parameters[$key];
                unset($parameters[$key]);
            }
        }

        if($new_parameters['RecordType'] === 'MX') {
            if(!isset($parameters['mx_pref'])) {
                $new_parameters['MXPref'.$index] = '10';
            } else {
                $new_parameters['MXPref'.$index] = $parameters['mx_pref'];
                unset($parameters['mx_pref']);
            }
        }

        foreach($optional_parameters as $key => $new_key) {
            if(isset($parameters[$key])) {
                $new_parameters[$new_key.$index] = $parameters[$key];
                unset($parameters[$key]);
            }
        }

        return $new_parameters;
    }

    /**
     * Sets email forwarding addresses. This will
     * only overwrite existing and append new entries. To
     * remove mailboxes use removeEmailForwarding()
     *
     * @param array $email_addresses
     *
     * @return \Namecheap\Response $response
     */
    public function setEmailForwarding($email_addresses)
    {
        $emails = array(
                'DomainName' => $this->getParameter('name')
            );

        $i = 1;
        foreach($email_addresses as $index => $email_config) {
            $mailbox = $email_config['mailbox'];
            $email_address = $email_config['forwarding_email'];
            $emails['MailBox'.$i] = $mailbox;
            $emails['ForwardTo'.$i] = $email_address;
            $i = $i + 1;
        }

        $command = 'namecheap.domains.dns.setEmailForwarding';
        $response = $this->processRequest($command, $emails);
        if($this->getStatus() === 'ok') {
            $attributes = $response['DomainDNSSetEmailForwardingResult']->attributes();
            $response = filter_var((string)$attributes['IsSuccess'], FILTER_VALIDATE_BOOLEAN);
        }
        return $this->createResponse($response);
    }

    /**
     * Removes email forwarding for specified mailboxes
     * Can also optionally remove by email addresses as well
     *
     * @param array $mailboxes
     * @param array $emails
     *
     * @return \Namecheap\Response $response
     */
    public function removeEmailForwarding($mailboxes = array(), $emails = array())
    {
        $current_email_forwarding = $this->getEmailForwarding();
        $current_settings = $current_email_forwarding->getResponse();
        foreach($current_settings as $index => $settings) {
            if(in_array($settings['mailbox'], $mailboxes)) {
                if(isset($current_settings[$index])) {
                    unset($current_settings[$index]);
                }
            }

            if(in_array($settings['forwarding_email'], $emails)) {
                if(isset($current_settings[$index])) {
                    unset($current_settings[$index]);
                }
            }
        }

        return $this->setEmailForwarding($current_settings);
    }
}

?>