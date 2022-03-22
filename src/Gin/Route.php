<?php
declare(strict_types=1);


namespace SwooleGin\Gin;


use SwooleGin\Gin\Context\ContextHandlerFuncInterface;

class Route implements RouteHandleInterface
{

    /**
     *
     * @param string $path
     * @param ContextHandlerFuncInterface[] $handlers
     * @param bool $isExtra
     */
    public function __construct(public string $path, public array $handlers = [], public bool $isExtra = false)
    {
        $this->path = rtrim($this->path);
    }

    public function match(string $path): ?Route
    {
        if ($this->isExtra && $this->path === rtrim($path)) {
            return $this;
        }
        if ($this->path === $path) {
            return $this;
        }

        return null;
    }


}