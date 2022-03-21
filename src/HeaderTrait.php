<?php

namespace SwooleGin;

use Psr\Http\Message\RequestInterface;

trait HeaderTrait
{
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

    public function withHeader($name, $value): RequestInterface
    {
        $this->header[strtolower($name)] = $value;
        return $this;
    }

    public function withAddedHeader($name, $value): RequestInterface
    {
        $name = strtolower($name);
        if (!isset($this->header[$name])) {
            $this->header[$name] = [];
        }

        $this->header[$name][] = $value;

        return $this;
    }

    public function withoutHeader($name)
    {
        unset($this->header[strtolower($name)]);
    }

}