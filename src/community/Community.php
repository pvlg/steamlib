<?php

namespace pvlg\steamlib\community;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use pvlg\steamlib\community\Inventory;
use pvlg\steamlib\Http;
use pvlg\steamlib\Steam;

/**
 * @property Inventory $inventory
 */
class Community extends CommunityBase
{
    public $base_uri = 'https://steamcommunity.com/';

    public function __construct(Steam $steam, $cookies, array $config = [])
    {
        $http = new Client([
            'base_uri' => $this->base_uri,
            'cookies' => $steam->cookies,
        ]);

        /** @var HandlerStack $stack */
        $stack = $http->getConfig('handler');
        $stack->push(Http::addHeaderAjax());
        $stack->push(Http::addHeader('Accept', '*/*'));
        $stack->push(Http::addHeader('Accept-Encoding', 'gzip, deflate'));
        $stack->push(Http::addHeader('Accept-Language', 'ru,en-US;q=0.8,en;q=0.6'));
        $stack->push(Http::addHeader('Connection', 'keep-alive'));
        $stack->push(Http::addHeader('User-Agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'));

        parent::__construct($steam, $http);
    }
}
