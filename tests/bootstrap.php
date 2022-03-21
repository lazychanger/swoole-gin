<?php

declare(strict_types=1);


include_once __DIR__ . '/../vendor/autoload.php';

$servOpts = new \SwooleGin\Options();
$servOpts->setAddr('0.0.0.0:8081');

$serv = new \SwooleGin\Server($servOpts);

$mux = new \SwooleGin\ServerMux();

$mux->handle(\SwooleGin\Request::METHOD_GET, '/', (new class implements \SwooleGin\HandleFuncInterface {
    public function __invoke(\Psr\Http\Message\ResponseInterface $rw, \Psr\Http\Message\RequestInterface $req)
    {
        $rw->withBody(new \SwooleGin\Stream\StringStream('hello world'));
    }

}));

$serv->setHandler($mux);
$serv->serve();