<?php

namespace tests\community\login;

use pvlg\steamlib\Steam;

class LoginTest extends \PHPUnit_Framework_TestCase
{
    public function testLogin()
    {
        $steam = new Steam();

        $login = $steam->community->login->login();
        if ($login) {
            $this->assertTrue($login);
        } else {
            $try = 0;
            while ($steam->community->login->captchaNeeded && $try < 1) {
                $try++;
                $steam->community->login->getCaptchaText();
                $login = $steam->community->login->login();
                if ($login) {
                    break;
                }
            }

            $this->assertTrue($login);
        }
    }

    public function testLogout()
    {
        $steam = new Steam();

        $this->assertTrue($steam->community->login->logout());
    }
}