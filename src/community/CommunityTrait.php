<?php

namespace pvlg\steamlib\community;

trait CommunityTrait
{
    public function __get($name)
    {
        $class = new \ReflectionClass($this);

        $className = $class->getNamespaceName() . '\\' . $name . '\\' . ucfirst($name);
        $this->$name = new $className();

        $this->$name->http = $this->http;
        return $this->$name;
    }
}