<?php

namespace pvlg\steamlib\community\inventory;

class RgInventory
{
    public $id;
    public $classid;
    public $instanceid;
    public $amount;
    public $pos;

    public function __construct($rgInventory)
    {
        $this->id = $rgInventory['id'];
        $this->classid = $rgInventory['classid'];
        $this->instanceid = $rgInventory['instanceid'];
        $this->amount = $rgInventory['amount'];
        $this->pos = $rgInventory['pos'];
    }
}