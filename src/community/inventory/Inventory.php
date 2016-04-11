<?php

namespace pvlg\steamlib\community\inventory;

class Inventory
{
    private $rgInventory = [];
    private $rgCurrency = [];
    private $rgDescriptions = [];
    private $items;

    public function __construct($inventory)
    {
        foreach ($inventory['rgInventory'] as $key => $val) {
            $this->rgInventory[$key] = new RgInventory($val);
        }
        foreach ($inventory['rgCurrency'] as $key => $val) {
            $this->rgCurrency[$key] = new RgCurrency($val);
        }
        foreach ($inventory['rgDescriptions'] as $key => $val) {
            $this->rgDescriptions[$key] = new RgDescriptions($val);
        }
    }

    public function getItems()
    {
        if ($this->items === null) {
            $this->items = [];
            foreach ($this->rgInventory as $key => $val) {
                $this->items[$key] = new Item($val);
            }
        }

        return new \ArrayIterator($this->items);
    }

    public function getRgInventory()
    {
        return new \ArrayIterator($this->rgInventory);
    }

    public function getRgCurrency()
    {
        return new \ArrayIterator($this->rgCurrency);
    }

    public function getRgDescriptions()
    {
        return new \ArrayIterator($this->rgDescriptions);
    }
}