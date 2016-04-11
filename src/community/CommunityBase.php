<?php

namespace pvlg\steamlib\community;

use GuzzleHttp\Client;
use pvlg\steamlib\Steam;

class CommunityBase
{
    /**
     * @var Steam
     */
    public $steam;

    /**
     * @var Client
     */
    public $http;


    public function __construct(Steam $steam, Client $http)
    {
        $this->steam = $steam;
        $this->http = $http;
    }

    public function __get($name)
    {
        $class = new \ReflectionClass($this);

        $className = $class->getNamespaceName() . '\\' . ucfirst($name);
        if (!class_exists($className)) {
            $className = $class->getNamespaceName() . '\\' . $name . '\\' . ucfirst($name);
        }

        return new $className($this->steam, $this->http);
    }
}
