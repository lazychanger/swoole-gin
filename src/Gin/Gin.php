<?php
declare(strict_types=1);


namespace SwooleGin\Gin;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SwooleGin\Gin\Context\ContextHandlerFuncInterface;
use SwooleGin\HandlerInterface;
use SwooleGin\Stream\StringStream;
use SwooleGin\Utils\HTTPStatus;

class Gin extends Router implements HandlerInterface
{

    protected ?ContextHandlerFuncInterface $onNotFound = null;

    public function ServerHTTP(ResponseInterface $rw, RequestInterface $req)
    {

        $handles = [];
        if (!empty($routes = $this->routes[$req->getMethod()])) {
            $path = $req->getUri()->getPath();
            foreach ($routes as $route) {
                if (!empty($current_route = $route->match($path))) {
                    $handles = $current_route->handlers;
                    break;
                }
            }
        }


        if (empty($handles)) {
            if (!empty($this->onNotFound)) {
                $handles[] = $this->onNotFound;
            } else {
                $rw->withBody(new StringStream('Not Found'));
                $rw->withStatus(HTTPStatus::StatusNotFound);
                return;
            }
        } else {
            $handles = array_merge($this->middlewares, $handles);
        }

        $context = new Context\Context($rw, $req, $handles);
        $context->next();
    }

    /**
     * @param ContextHandlerFuncInterface $onNotFound
     */
    public function setOnNotFound(ContextHandlerFuncInterface $onNotFound): void
    {
        $this->onNotFound = $onNotFound;
    }
}