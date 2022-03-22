<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SwooleGin\Stream\StringStream;

class Response implements ResponseInterface
{
    use HeaderTrait;

    protected int $statusCode = 200;
    protected string $reasonPhrase = 'OK';
    protected string $version = '1.1';

    protected StreamInterface $stream;

    public static array $statusReasonPhrase = [
        0 => 'Server Error',
        200 => 'OK',
        403 => 'Authorized Failed',
        404 => 'Not Found',
        500 => 'Server Error',
    ];

    public function getProtocolVersion(): string
    {
        return $this->version;
    }

    public function withProtocolVersion($version): ResponseInterface
    {
        $this->version = $version;
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return empty($this->stream) ? new StringStream('') : $this->stream;
    }

    public function withBody(StreamInterface $body): ResponseInterface
    {
        $this->stream = $body;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): Response|static
    {
        $this->statusCode = $code;
        !empty($reasonPhrase) && $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

}