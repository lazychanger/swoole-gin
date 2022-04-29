<?php

namespace SwooleGin;

use Psr\Http\Message\StreamInterface;
use SwooleGin\Stream\StringStream;

trait MessageTrait
{
    protected string $protocol_version = '1.1';

    protected StreamInterface $stream;

    /**
     * @var array<string, string[]>
     */
    protected array $header = [];

    /**
     * @return array<string, string[]>
     */
    public function getHeaders(): array
    {
        return $this->header;
    }

    public function hasHeader($name): bool
    {
        return isset($this->header[$name]);
    }

    /**
     * @param $name
     * @return string[]
     */
    public function getHeader($name): array
    {
        return $this->header[strtolower($name)] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode('; ', $this->header[strtolower($name)] ?? []);
    }

    public function withHeader($name, $value): self
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $this->header[strtolower($name)] = $value;
        return $this;
    }

    public function withAddedHeader($name, $value): self
    {
        $name = strtolower($name);
        if (!isset($this->header[$name])) {
            $this->header[$name] = [];
        }

        $this->header[$name][] = $value;

        return $this;
    }

    public function withoutHeader($name): void
    {
        unset($this->header[strtolower($name)]);
    }


    public function getProtocolVersion(): string
    {
        return $this->protocol_version;
    }

    public function withProtocolVersion($version): self
    {
        $this->protocol_version = $version;
        return $this;
    }

    public function getBody(): StreamInterface
    {
        return empty($this->stream) ? new StringStream('') : $this->stream;
    }

    public function withBody(StreamInterface $body): static
    {
        $this->stream = $body;
        return $this;
    }

}