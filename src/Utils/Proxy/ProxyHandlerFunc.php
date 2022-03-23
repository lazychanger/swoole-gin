<?php
declare(strict_types=1);


namespace SwooleGin\Utils\Proxy;


use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SwooleGin\HandlerFuncInterface;

class ProxyHandlerFunc implements HandlerFuncInterface
{
    public function __construct(protected Proxy $proxy)
    {
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function __invoke(ResponseInterface $rw, RequestInterface $req): ResponseInterface
    {
        return $this->proxy->handler($req);
    }

}