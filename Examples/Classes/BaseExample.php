<?php

namespace Examples\Classes;

use Examples\Classes\ExampleUtils;

class BaseExample
{
    private $client;

    public function __construct()
    {
        $this->setClient(ExampleUtils::getClient());
    }

    private function setClient(\LewNelson\Namecheap\Client $client)
    {
        $this->client = $client;
    }

    protected function getClient()
    {
        return $this->client;
    }
}

?>