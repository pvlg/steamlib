<?php

namespace tests\community;

use pvlg\steamlib\Steam;

class CommunityTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadIndexPage()
    {
        $steam = new Steam();
        $response = $steam->community->http->get('/');

        $this->assertEquals(200, $response->getStatusCode());
    }
}