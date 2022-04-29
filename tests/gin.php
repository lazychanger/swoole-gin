<?php

declare(strict_types=1);


use SwooleGin\Gin\Context\Context;
use SwooleGin\Gin\Context\ContextHandlerFuncInterface;
use SwooleGin\Gin\Gin;
use SwooleGin\Gin\Middleware\FaviconMiddleware;
use SwooleGin\Options;
use SwooleGin\Server;
use SwooleGin\Stream\StringStream;
use SwooleGin\Utils\HTTPStatus;

include_once __DIR__ . '/../vendor/autoload.php';

$servOpts = new Options();
$servOpts->setAddr('0.0.0.0:8082');

$serv = new Server($servOpts);

//$mux = new ServerMux();
//
//$mux->handle(HTTPMethod::GET, '/', (new class implements HandlerFuncInterface {
//    public function __invoke(ResponseInterface $rw, RequestInterface $req)
//    {
//        $rw->withBody(new StringStream('hello world'));
//    }
//
//}));

$engine = new Gin();
$engine->use(
    (new FaviconMiddleware),
// 鉴权
    (new class implements ContextHandlerFuncInterface {
        public function __invoke(Context $context)
        {
            if ($context->query('token') !== '123456') {
                $context->response->withBody(new StringStream('authorized failed'));
                $context->response->withStatus(HTTPStatus::StatusForbidden);
                $context->abort();
            }
        }
    }),
    // 打印响应内容
    (new class implements ContextHandlerFuncInterface {
        public function __invoke(Context $context)
        {
            $context->next();

            $body = $context->response->getBody()->getContents();
            echo 'resp:', $body, PHP_EOL;
            $context->response->withBody(new StringStream($body));
        }
    }),
);
$engine->GET('/hello', (new class implements ContextHandlerFuncInterface {
    public function __invoke(Context $context)
    {
        $context->Raw(HTTPStatus::StatusOK, 'hello world');
    }
}));

$engine->setOnNotFound((new class implements ContextHandlerFuncInterface {
    public function __invoke(Context $context)
    {
        $context->JSON(HTTPStatus::StatusOK, ['code' => HTTPStatus::StatusNotFound, 'msg' => 'not found']);
    }

}));

$serv->setHandler($engine);
$serv->serve();