<?php

namespace pvlg\steamlib;

trait SteamTrait
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