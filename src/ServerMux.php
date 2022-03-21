<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SwooleGin\Exception\NotFoundException;
use SwooleGin\Middleware\MiddlewareInterface;

class ServerMux implements HandlerInterface
{
    /**
     * @var HandleFuncInterface|null
     */
    protected ?HandleFuncInterface $onNotFound = null;

    /**
     * @var MiddlewareInterface[]
     */
    protected array $middlewares = [];

    public function __construct(MiddlewareInterface ...$middlewares)
    {
        array_push($this->middlewares, ...$middlewares);
    }

    /**
     * @var array<string, array<string, callable>>
     */
    private array $routes = [];

    /**
     * @throws NotFoundException
     */
    public function ServerHTTP(ResponseInterface $rw, RequestInterface $req)
    {
//        foreach ($this->middlewares as $middleware) {
//            $middleware->handle($rw, $req, function (){
//
//            });
//        }

        $method = strtolower($req->getMethod());
        $path = $req->getUri()->getPath();

        if (!empty($this->routes[$method]) && !empty($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path], $rw, $req);
            return;
        }

        if (!empty($this->onNotFound)) {
            call_user_func($this->onNotFound, $rw, $req);
        }

        throw new NotFoundException();
    }

    public function handle(string $method, string $path, HandleFuncInterface $cb)
    {
        $method = strtolower($method);
        if (empty($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        if (!empty($this->routes[$method][$path])) {
            throw new \RuntimeException(sprintf('Handle not allowed repeat. method: %s; path: %s', $method, $path));
        }

        $this->routes[$method][$path] = $cb;
    }

    /**
     * @param HandleFuncInterface|null $onNotFound
     */
    public function setOnNotFound(?HandleFuncInterface $onNotFound): void
    {
        $this->onNotFound = $onNotFound;
    }
}