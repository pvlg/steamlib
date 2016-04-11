<?php

namespace pvlg\steamlib;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

/**
 * Class Base
 */
class Base
{
    /**
     * @var Http
     */
    public $http;

    public function __construct(array &$config = [])
    {
        if (isset($config['http'])) {
            if ($config['http']) {
                $this->http = $config['http'];
            } else {
                throw new Exception;
            }
        } else {
            
            $this->http = new Client([
                'base_uri' => 'https://steamcommunity.com',
                'cookies' => true
            ]);
            /** @var HandlerStack $stack */
            $stack = $this->http->getConfig('handler');
            $stack->push(add_header_ajax());
            $stack->push(add_header('Accept', '*/*'));
            $stack->push(add_header('Accept-Encoding', 'gzip, deflate'));
            $stack->push(add_header('Accept-Language', 'ru,en-US;q=0.8,en;q=0.6'));
            $stack->push(add_header('Connection', 'keep-alive'));
            $stack->push(add_header('User-Agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36'));

            $config['http'] = $this->http;
        }
    }
}

function add_header_ajax()
{
    return function (callable $handler) {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (isset($options['ajax']) && $options['ajax'] == true) {
                $request = $request->withHeader('X-Requested-With', 'XMLHttpRequest');
            }
            return $handler($request, $options);
        };
    };
}

function add_header($header, $value)
{
    return function (callable $handler) use ($header, $value) {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler, $header, $value) {
            $request = $request->withHeader($header, $value);
            return $handler($request, $options);
        };
    };
}