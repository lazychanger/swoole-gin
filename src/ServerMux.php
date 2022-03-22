<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use SwooleGin\Exception\NotFoundException;

class ServerMux implements HandlerInterface
{
    /**
     * @var HandlerFuncInterface|null
     */
    protected ?HandlerFuncInterface $onNotFound = null;


    /**
     * @var array<string, array<string, callable>>
     */
    private array $routes = [];


    /**
     * @throws NotFoundException
     */
    public function ServerHTTP(ResponseInterface $rw, RequestInterface $req)
    {
        $method = strtolower($req->getMethod());
        $path = $req->getUri()->getPath();

        if (!empty($this->routes[$method]) && !empty($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path], $rw, $req);
            return;
        }

        if (!empty($this->onNotFound)) {
            call_user_func($this->onNotFound, $rw, $req);
            return;
        }

        throw new NotFoundException();
    }

    public function handle(string $method, string $path, HandlerFuncInterface $cb)
    {
        $method = strtolower($method);
        if (empty($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        if (!empty($this->routes[$method][$path])) {
            throw new RuntimeException(sprintf('Handle not allowed repeat. method: %s; path: %s', $method, $path));
        }

        $this->routes[$method][$path] = $cb;
    }

    /**
     * @param HandlerFuncInterface|null $onNotFound
     */
    public function setOnNotFound(?HandlerFuncInterface $onNotFound): void
    {
        $this->onNotFound = $onNotFound;
    }
}