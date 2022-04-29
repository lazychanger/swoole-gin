<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Http\Message\RequestInterface;
use SebastianBergmann\CodeCoverage\Report\PHP;

class Request extends \GuzzleHttp\Psr7\Request implements RequestInterface
{

    public static function parseRequest(string $data): RequestInterface
    {
        $data = explode(PHP_EOL, $data);

        // first line is protocol, look like: GET / HTTP/1.1
        // [method] [path] [protocol]
        [$method, $path, $protocol] = explode(' ', array_shift($data));

        // header is key-values array, look like: Host: test.com
        $header = [];

        while (true) {
            $line = array_shift($data);

            if (empty($line) || empty(trim($line))) {
                break;
            }
            [$key, $value] = explode(':', $line);

            // the header value will split ';' and trim space
            $header[strtolower($key)] = array_filter(array_map(function ($item) {
                return empty($item) ? $item : trim($item);
            }, explode(';', $value)));
        }


        return new self($method, $path, $header, $data[0], $protocol);
    }
}