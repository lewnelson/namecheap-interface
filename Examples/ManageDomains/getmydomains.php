<?php

/**
 * This example will build a JSON containing all domains,
 * pagination info and the status. If there is a Namecheap
 * error reported this will be recored under error. Just
 * include this file in your project to run the example code
 */
require_once(__DIR__.'/../autoload.php');
$manage_domains_examples = new \Examples\Classes\ManageDomainsExamples();

$my_domains = $manage_domains_examples->getMyDomains();
header('Content-Type: application/json');
echo json_encode($my_domains, JSON_PRETTY_PRINT);

?>