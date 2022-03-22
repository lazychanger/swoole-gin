<?php
declare(strict_types=1);


namespace SwooleGin\Gin\Middleware;


use SwooleGin\Gin\Context\Context;
use SwooleGin\Gin\Context\ContextHandlerFuncInterface;
use SwooleGin\Stream\StringStream;
use SwooleGin\Utils\HTTPStatus;

class FaviconMiddleware implements ContextHandlerFuncInterface
{
    public function __invoke(Context $context)
    {
        echo 'favicon:', $context->request->getUri()->getPath(), PHP_EOL;
        if ($context->request->getUri()->getPath() === '/favicon.ico') {
            $context->response
                ->withStatus(HTTPStatus::StatusOK)
                ->withBody(new StringStream(''));
            $context->abort();
        }
    }

}