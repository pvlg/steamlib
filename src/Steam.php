<?php

namespace pvlg\steamlib;

use GuzzleHttp\Cookie\CookieJar;
use pvlg\steamlib\api\Api;
use pvlg\steamlib\community\Community;
use pvlg\steamlib\store\Store;

class Steam
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var Api
     */
    public $api;

    /**
     * @var Community
     */
    public $community;

    /**
     * @var Store
     */
    public $store;

    /**
     * @var CookieJar
     */
    public $cookies;

    private $profileId;

    public function __construct(array $config = [])
    {
        if (isset($config['cookies']) && is_array($config['cookies'])) {
            $this->cookies = new CookieJar(true, $config['cookies']);
        } else {
            $this->cookies = new CookieJar();
        }

        if (isset($config['username']) && isset($config['password'])) {
            $this->username = $config['username'];
            $this->password = $config['password'];
        }

        if (isset($config['profileId'])) {
            $this->setProfileId($config['profileId']);
        }

        $this->api = new Api($this, $config);
        $this->community = new Community($this, $config);
        $this->store = new Store($this, $config);
    }

    public function getProfileId()
    {
        return $this->profileId;
    }

    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }
}