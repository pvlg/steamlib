<?php

namespace pvlg\steamlib;

use Psr\Http\Message\RequestInterface;

class Http
{
    public static function addHeaderAjax()
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

    public static function addHeader($header, $value)
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
}