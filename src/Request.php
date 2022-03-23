<?php
declare(strict_types=1);


namespace SwooleGin;


use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use SwooleGin\Stream\StringStream;

class Request implements RequestInterface
{
    use MessageTrait;

    const EOL = '\n';

    private UriInterface $uri;
    private ?string $requestTarget = null;

    public function getRequestTarget(): string
    {
        if (!empty($this->requestTarget)) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }
        if ($this->uri->getQuery() != '') {
            $target .= '?' . $this->uri->getQuery();
        }

        $this->requestTarget = $target;

        return $target;
    }

    public function withRequestTarget($requestTarget): RequestInterface
    {
        $this->requestTarget = $requestTarget;

        return $this;

    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): RequestInterface
    {
        $this->method = $method;
        return $this;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): RequestInterface
    {
        $this->uri = $uri;

        if (!$preserveHost || !$this->hasHeader('host')) {
            $host = $uri->getHost();
            if (!empty($host)) {
                if (($port = $this->uri->getPort()) !== null) {
                    $host .= ':' . $port;
                }
                $this->header = ['host' => [$host]] + $this->header;
            }
        }


        return $this;
    }

    /**
     * @param string $method
     * @param string $path
     * @param string $protocol_version
     * @param array<string, string[]> $header
     * @param StreamInterface|null $stream
     */
    public function __construct(
        protected string $method,
        string $path,
        string $protocol_version = '1.1',
        array $header = [],
        ?StreamInterface $stream = null,
    ) {
        $this->protocol_version = $protocol_version;
        $this->stream = empty($stream) ? new StringStream('') : $stream;
        $this->uri = new Uri($path);
        $this->header = $header;

        if ($this->hasHeader('host')) {
            $this->uri->withHost($this->getHeader('host')[0]);
        }
    }

    /**
     * @param string $data
     * @return RequestInterface
     */
    public static function parseRequest(string $data): RequestInterface
    {
        $data = explode(self::EOL, $data);

        // first line is protocol, look like: GET / HTTP/1.1
        // [method] [path] [protocol]
        [$method, $path, $protocol] = explode(' ', array_pop($data));

        // header is key-values array, look like: Host: test.com
        $header = [];

        while (true) {
            $line = array_pop($data);

            if (empty($line) || empty(trim($line))) {
                break;
            }
            [$key, $value] = explode(':', $line);

            // the header value will split ';' and trim space
            $header[strtolower($key)] = array_map(function ($item) {
                return !empty($item) ? $item : trim($item);
            }, explode(';', $value));
        }

        $stream = null;

        if (!empty($data)) {
            $stream = new StringStream($data[0]);
        }

        return new self($method, $path, explode('/', $protocol)[1], $header, $stream);
    }
}