<?php

namespace SwooleGin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HandlerFuncInterface
{
    public function __invoke(ResponseInterface $rw, RequestInterface $req);
}