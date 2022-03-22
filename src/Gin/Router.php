<?php
declare(strict_types=1);


namespace SwooleGin\Gin;


use SwooleGin\Gin\Context\ContextHandlerFuncInterface;
use SwooleGin\Utils\HTTPMethod;

class Router implements RouteHandleInterface
{
    /**
     * @var array<string, Route|Router[]>
     */
    protected array $routes = [];

    protected array $middlewares = [];

    public function GET(string $path, ContextHandlerFuncInterface $handlerFunc, bool $isExtra = false)
    {
        $this->handle(HTTPMethod::GET, $path, $handlerFunc, $isExtra);
    }

    public function POST(string $path, ContextHandlerFuncInterface $handlerFunc, bool $isExtra = false)
    {
        $this->handle(HTTPMethod::POST, $path, $handlerFunc, $isExtra);
    }

    public function DELETE(string $path, ContextHandlerFuncInterface $handlerFunc, bool $isExtra = false)
    {
        $this->handle(HTTPMethod::DELETE, $path, $handlerFunc, $isExtra);
    }

    public function PUT(string $path, ContextHandlerFuncInterface $handlerFunc, bool $isExtra = false)
    {
        $this->handle(HTTPMethod::PUT, $path, $handlerFunc, $isExtra);
    }

    public function OPTION(string $path, ContextHandlerFuncInterface $handlerFunc, bool $isExtra = false)
    {
        $this->handle(HTTPMethod::OPTIONS, $path, $handlerFunc, $isExtra);
    }

    public function CONNECT(string $path, ContextHandlerFuncInterface $handlerFunc, bool $isExtra = false)
    {
        $this->handle(HTTPMethod::CONNECT, $path, $handlerFunc, $isExtra);
    }

    public function use(ContextHandlerFuncInterface ...$handlerFuncs)
    {
        array_push($this->middlewares, ... $handlerFuncs);
    }

    public function handle(
        string $method,
        string $path,
        ContextHandlerFuncInterface $handlerFunc,
        bool $isExtra = false
    ) {
        if (empty($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        $this->routes[$method][] = new Route($path, [$handlerFunc], $isExtra);
    }

    public function match(string $path): ?Route
    {
        return null;
    }


}