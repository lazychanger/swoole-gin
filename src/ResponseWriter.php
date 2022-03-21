<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Http\Message\ResponseInterface;
use Swoole\Server as SwooleServer;

class ResponseWriter
{
    public static function write(SwooleServer $server, int $fd, ResponseInterface $response)
    {
        // @see https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Session
        // response first line is state line.
        // [protocol] [statusCode] [getReasonPhrase]
        $server->send($fd, sprintf(
                "HTTP/%s %d %s \r\n",
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase(),
            )
        );

        // second is response header
        foreach ($response->getHeaders() as $key => $headers) {
            $server->send($fd, sprintf("%s: %s\r\n", ucfirst($key), implode('; ', $headers)));
        }

        // if response has body
        if ($response->getBody()->getSize() > 0) {
            $server->send($fd, sprintf("Content-Size: %d\r\n", $response->getBody()->getSize()));

            $server->send($fd, "\r\n");
            $server->send($fd, $response->getBody()->getContents());
            $server->send($fd, "\r\n");
        }

    }
}