<?php

declare(strict_types=1);


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use SwooleGin\HandlerFuncInterface;
use SwooleGin\Options;
use SwooleGin\Server;
use SwooleGin\ServerMux;
use SwooleGin\Stream\StringStream;
use SwooleGin\Utils\HTTPMethod;

include_once __DIR__ . '/../vendor/autoload.php';

$servOpts = new Options();
$servOpts->setAddr('0.0.0.0:8082');

$serv = new Server($servOpts);

$mux = new ServerMux();

$mux->handle(HTTPMethod::GET, '/', (new class implements HandlerFuncInterface {
    public function __invoke(ResponseInterface $rw, RequestInterface $req): ResponseInterface
    {
        \SwooleGin\Container::getContainer()->get('logger')->info($req->getMethod());
        return $rw->withBody(new StringStream('hello world'));
    }

}));

$mux->handle(HTTPMethod::GET, '/hello', (new class implements HandlerFuncInterface {
    public function __invoke(ResponseInterface $rw, RequestInterface $req): ResponseInterface
    {
        \SwooleGin\Container::getContainer()->get('logger')->info($req->getMethod());
        return $rw->withBody(new StringStream('route: hello'));
    }
}));

$serv->setHandler($mux);
$serv->serve();