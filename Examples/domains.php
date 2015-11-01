<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *  This file is intended to demonstrate how you can utilise
 *  the Namecheap domain methods, by declaring all available
 *  functions and then declaring functions to run them
 *  with dummy parameters.
 *
 *  You can try any of these functions out live by just
 *  changing the config array to match your namecheap config.
 *  I advise you just use the development environment and setup
 *  a namecheap sandbox account to try out the available
 *  functions.
 *
 *
 */

include_once(getcwd().'/../vendor/autoload.php');

$config = array(
        // CHANGE ME
        'api_key' => 'your_namecheap_api_key',
        'api_username' => 'your_namecheap_username',
        'environment' => 'development',     // Set to production to use live namecheap endpoint, otherwise development uses Namecheaps sandbox environment which requires a separate sandbox account
        'client_ip' => '0.0.0.0'    // Should be set to your public IP. Namecheap also requires this IP to be  whitelisted
    );



/*******************************************************************
 *************    DECLARING AVAILABLE FUNCTIONS     ****************
 *******************************************************************/

/**
 *  This will return all of your domains as an
 *  array of domain objects which can be used
 *  to view and edit domain configuration
 *
 *  @param \Namecheap\NamecheapMethodTypesInterface $instance
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function getDomainObjects(\Namecheap\NamecheapMethodTypesInterface $instance) {
    return $instance->getList();
}

/**
 *  Get domain object for specific domain
 *
 *  @param \Namecheap\NamecheapMethodTypesInterface $instance
 *  @throws \Exception
 *
 *  @param string $domain
 */
function getDomainObject(\Namecheap\NamecheapMethodTypesInterface $instance, $domain) {
    return $instance->getDomain($domain);
}

/**
 *  Sets domain to use Namecheaps default nameservers
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function setDefaultNameservers(\Namecheap\Objects\Domains $domain_object) {
    return $domain_object->setDefault();
}

/**
 *  Sets domain to use custom specified nameservers
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $nameservers
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function setCustomNameservers(\Namecheap\Objects\Domains $domain_object, $nameservers) {
    return $domain_object->setCustom($nameservers);
}

/**
 *  Removes specified nameservers
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $nameservers
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function removeNameServers(\Namecheap\Objects\Domains $domain_object, $nameservers) {
    return $domain_object->removeNameServers($nameservers);
}

/**
 *  Gets list of nameservers for domain
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function getNameServers(\Namecheap\Objects\Domains $domain_object) {
    return $domain_object->getList();
}

/**
 *  Gets email forwarding settings on domain
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function getEmailForwarding(\Namecheap\Objects\Domains $domain_object) {
    return $domain_object->getEmailForwarding();
}

/**
 *  Remove mail forwarding records based on mailboxes and or emails
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $mailboxes
 *  @param array $emails
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function removeEmailForwarding(\Namecheap\Objects\Domains $domain_object, $mailboxes =array(), $emails = array()) {
    return $domain_object->removeEmailForwarding($mailboxes, $emails);
}

/**
 *  Sets email forwarding settings on domain
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $mail_settings
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function setEmailForwarding(\Namecheap\Objects\Domains $domain_object, $mail_settings) {
    return $domain_object->setEmailForwarding($mail_settings);
}

/**
 *  Gets Host records except MX and MXE records
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function getHostRecords(\Namecheap\Objects\Domains $domain_object) {
    return $domain_object->getHostRecords();
}

/**
 *  Gets MX and MXE records
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function getMxRecords(\Namecheap\Objects\Domains $domain_object) {
    return $domain_object->getMxRecords();
}

/**
 *  Set Host records cannot be of type MX or MXE
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $hosts
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function setHostRecords(\Namecheap\Objects\Domains $domain_object, $hosts) {
    return $domain_object->setHostRecords();
}

/**
 *  Set MX and MXE records. Hosts must be of type MX or MXE
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $hosts
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function setMxRecords(\Namecheap\Objects\Domains $domain_object, $hosts) {
    return $domain_object->setMxRecords();
}

/**
 *  Delete Host records i.e. not MX or MXE records,
 *  by an array of host_names/subdomains
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $hosts
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function deleteHostRecords(\Namecheap\Objects\Domains $domain_object, $hosts) {
    return $domain_object->deleteHostRecords();
}

/**
 *  Delete MX records, i.e. only MX and MXE records,
 *  by array of host_names/subdomains
 *
 *  @param \Namecheap\Objects\Domains $domain_object
 *  @param array $hosts
 *  @throws \Exception
 *
 *  @return \Namecheap\Response
 */
function deleteMxHosts(\Namecheap\Objects\Domains $domain_object, $hosts) {
    return $domain_object->deleteMxHosts();
}




/*******************************************************************
 ***************    DECLARING DUMMY FUNCTIONS     ******************
 *******************************************************************/

/**
 *  Return response object with our domain objects
 *
 *  @throws \Exception with namecheap error if applicable
 *
 */
function getAllDomainObjects($config) {
    // Instantiate our main class
    $namecheap = new \Namecheap\Namecheap();

    // Create the type of object we want, in this case we
    // want a Domains object
    $instance = $namecheap->create('Domains', $config);

    // Get all of our domains
    $domains = getDomainObjects($instance);

    // Check the API call was successful. The \Namecheap\Response
    // object stores a status property which is either set to
    // 'ok' for a successful response or 'error' when something
    // the request was unsuccessful.
    if($domains->getStatus() === 'error') {
        // If there were any errors the response is stored in the
        // response property as a string. This is the error string
        // output from namecheap.
        throw new \Exception($domains->getResponse());
    } else {
        // The request was successful so return our response object
        return $domains;
    }
}

/**
 *  An example of getting all domains then getting all host and mx
 *  records for them.
 *
 */
function getAllRecords($config) {
    // Get all our domains as a \Namecheap\Response object
    $domains = getAllDomainObjects($config);

    // Parse out the response from our \Namecheap\Response object
    // and iterate through each domain_object getting host records
    // and mx records
    foreach($domains->getResponse() as $index => $domain_object) {
        // Access our objects parameters to get the domain name
        // which is stored as 'name'
        $domain_name = $domain_object->getParameter('name');

        // Get the host records for the domain
        $host_records = getHostRecords($domain_object);
        // Lets check that the response was successful
        if($host_records->getStatus() !== 'ok') {
            $all_records[$domain_name]['errors']['host_records'] = $host_records->getResponse();
        } else {
            $all_records[$domain_name]['host_records'] = $host_records->getResponse();
        }

        // Get the MX records for the domain
        $mx_records = getMxRecords($domain_object);
        // Lets check that the response was successful
        if($mx_records->getStatus() !== 'ok') {
            $all_records[$domain_name]['errors']['mx_records'] = $mx_records->getResponse();
        } else {
            $all_records[$domain_name]['mx_records'] = $mx_records->getResponse();
        }
    }

    // Return our compiled results
    return $all_records;
}

/**
 *  An example of setting custom nameservers on all of our domains
 *
 */
function setAllCustomNameservers($config) {
    // Get all our domains as a \Namecheap\Response object
    $domains = getAllDomainObjects($config);

    // Set the array of our custom nameservers we want to use
    $nameservers = array(
            'nameserver1.dns.com',
            'nameserver2.dns.com',
            'nameserver3.dns.com'
        );

    // Declare our array for storing our results in
    $results = array();

    // Parse out the response from our \Namecheap\Response object
    // and iterate through each domain_object getting host records
    // and mx records
    foreach($domains->getResponse() as $index => $domain_object) {
        // Access our objects parameters to get the domain name
        // which is stored as 'name'
        $domain_name = $domain_object->getParameter('name');

        // Update the nameservers for our domain
        $response = setCustomNameservers($domain_object, $nameservers);

        // Update our results
        $results[$domain_name]['status'] = $response->getStatus();
        $results[$domain_name]['response'] = $response->getResponse();

        // This property stores some interesting information regarding our request
        // such as execution time, time difference on the server, the server we sent
        // the request to and the URL of the request.
        $results[$domain_name]['request_information'] = $response->getRequestInformation();
    }

    // Return our compiled results
    return $results;
}

/**
 *  An example of setting all our domains to use Namecheaps default nameservers
 *
 */
function setAllDefaultNameservers($config) {
    // Get all our domains as a \Namecheap\Response object
    $domains = getAllDomainObjects($config);

    // Declare our array for storing our results in
    $results = array();

    // Parse out the response from our \Namecheap\Response object
    // and iterate through each domain_object getting host records
    // and mx records
    foreach($domains->getResponse() as $index => $domain_object) {
        // Access our objects parameters to get the domain name
        // which is stored as 'name'
        $domain_name = $domain_object->getParameter('name');

        // Update the nameservers for our domain
        $response = setDefaultNameservers($domain_object);

        // Update our results
        $results[$domain_name]['status'] = $response->getStatus();
        $results[$domain_name]['response'] = $response->getResponse();

        // This property stores some interesting information regarding our request
        // such as execution time, time difference on the server, the server we sent
        // the request to and the URL of the request.
        $results[$domain_name]['request_information'] = $response->getRequestInformation();
    }

    // Return our compiled results
    return $results;
}

/**
 *  An example of changing all email forwarding to one setting
 *  on all of our domains
 *
 */
function setAllEmailForwarding($config) {
    // Get all our domains as a \Namecheap\Response object
    $domains = getAllDomainObjects($config);

    // Set the array configuration of our email forwarding
    // settings which will be applied to all of our domains.
    // These settings will translate to the following
    //
    // domainadmin@domain.com   --> myemail@gmail123.com
    // enquiries@domain.com     --> enquiries@gmail123.com
    //
    // The domain.com will be replaced by each domain.
    //
    $email_forwarding_configuration = array(
            array(
                    'mailbox' => 'domainadmin',
                    'forwarding_email' => 'myemail@gmail.com'
                ),
            array(
                    'mailbox' => 'enquiries',
                    'forwarding_email' => 'enquiries@gmail.com'
                )
        );

    // Declare our array for storing our results in
    $results = array();

    // Parse out the response from our \Namecheap\Response object
    // and iterate through each domain_object getting host records
    // and mx records
    foreach($domains->getResponse() as $index => $domain_object) {
        // Access our objects parameters to get the domain name
        // which is stored as 'name'
        $domain_name = $domain_object->getParameter('name');

        // Get our current settings so we can delete them
        $current_settings = getEmailForwarding($domain_object);
        if($current_settings->getStatus() === 'ok') {
            // Make sure we don't delete these new settings later on
            $new_mailboxes = array();
            foreach($email_forwarding_configuration as $key => $value) {
                $new_mailboxes[] = $value['mailbox'];
            }

            // Set our new mail settings
            $set = setEmailForwarding($domain_object, $email_forwarding_configuration);

            // Check it was set successfully
            if($set->getStatus() === 'ok') {
                // Declare our array to store our mailbox values for deleting
                $delete = array();

                // Iterate through our current settings
                foreach($current_settings->getResponse() as $key => $current_mail_settings) {
                    // We don't want to delete the mailboxes we just added!
                    if(!in_array($current_mail_settings['mailbox'], $new_mailboxes)) {
                        $delete[] = $current_mail_settings['mailbox'];
                    }
                }

                // Delete all of our old records if we have any
                if(!empty($delete)) {
                    $delete_response = removeEmailForwarding($domain_object, $delete);

                    // Add our response to the results
                    $results[$domain_name] = array(
                            'status' => $delete_response->getStatus(),
                            'response' => $delete_response->getResponse()
                        );
                } else {
                    $results[$domain_name] = array(
                            'status' => 'ok',
                            'response' => true
                        );
                }
            } else {
                $results[$domain_name] = array(
                        'status' => $set->getStatus(),
                        'response' => $set->getResponse()
                    );
            }
        } else {
            $results[$domain_name] = array(
                    'status' => $current_settings->getStatus(),
                    'response' => $current_settings->getResponse()
                );
        }
    }

    // Return our compiled results
    return $results;
}


try {
    var_dump('Uncomment some of the functions inside the try catch block to test them out.');
    /**
     *  uncomment lines below to run the functions
     */
    //var_dump(getAllDomainObjects($config));
    //var_dump(getAllRecords($config));
    //var_dump(setAllCustomNameservers($config));
    //var_dump(setAllDefaultNameservers($config));
    //var_dump(setAllEmailForwarding($config));
} catch(\Exception $exception) {
    var_dump($exception->getMessage());
}