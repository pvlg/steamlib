<?php

namespace tests\store;

use pvlg\steamlib\Steam;

class StoreTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadIndexPage()
    {
        $steam = new Steam();
        $response = $steam->store->http->get('/');

        $this->assertEquals(200, $response->getStatusCode());
    }
}