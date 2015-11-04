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
class EmailForwarding extends BaseDomains
{
    /**
     * Gets email forwarding for domain
     *
     * @return \LewNelson\Namecheap\Response $response
     */
    public function getEmailForwarding()
    {
        $parameters = array(
                'DomainName' => $this->getParameter('name')
            );
        $command = 'namecheap.domains.dns.getEmailForwarding';
        $response = $this->processRequest($command, $parameters);
        $forwarding_response = array();

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

        return $this->createResponse($forwarding_response);
    }

    /**
     * Sets email forwarding addresses. This will
     * only overwrite existing and append new entries. To
     * remove mailboxes use removeEmailForwarding()
     *
     * @param array $email_addresses
     *
     * @return \LewNelson\Namecheap\Response $response
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

        $attributes = $response['DomainDNSSetEmailForwardingResult']->attributes();
        $response = filter_var((string)$attributes['IsSuccess'], FILTER_VALIDATE_BOOLEAN);
        
        return $this->createResponse($response);
    }

    /**
     * Removes email forwarding for specified mailboxes
     * Can also optionally remove by email addresses as well
     *
     * @param array $mailboxes
     * @param array $emails
     *
     * @return \LewNelson\Namecheap\Response $response
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