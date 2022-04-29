<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Http\Message\ResponseInterface;
use Swoole\Server as SwooleServer;

class ResponseWriter
{
    public static function write(SwooleServer $server, int $fd, ResponseInterface $response, int $buffer_size = 4096)
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

        // if response has body
        $content_size = $response->getBody()->getSize();
        if (!empty($header_content_size = $response->getHeader('Content-Size'))) {
            $content_size = intval($header_content_size[0]);
        } else {
            $response->withHeader('Content-Size', $content_size);
        }

        $server->send($fd, sprintf("Content-Size: %d\r\n", $content_size));

        // second is response header
        foreach ($response->getHeaders() as $key => $headers) {
            $server->send($fd, sprintf("%s: %s\r\n", ucfirst($key), implode('; ', $headers)));
        }


        if ($content_size > 0) {
            $server->send($fd, "\r\n");

            $bucket_size = ceil($content_size / $buffer_size);

            // buffer
            for ($i = 0; $i < $bucket_size; $i++) {
                $server->send($fd, $response->getBody()->read($buffer_size));
            }

            $server->send($fd, "\r\n");
        }

    }
}