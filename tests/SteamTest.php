<?php

namespace tests;

use pvlg\steamlib\Steam;

class SteamTest extends \PHPUnit_Framework_TestCase
{
    public function testLogin()
    {
        $steam = new Steam();

        $this->assertTrue($steam instanceof Steam);
    }
}
