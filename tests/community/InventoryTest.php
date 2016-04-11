<?php

namespace tests\community;

use pvlg\steamlib\community\inventory\Inventory;
use pvlg\steamlib\Steam;

class InventoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInventory()
    {
        $steam = new Steam();
        $inventory = $steam->community->inventory->getInventory(570, 2);
        $this->assertInstanceOf(Inventory::class, $inventory);
    }
}