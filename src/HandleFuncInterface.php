<?php

namespace SwooleGin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HandleFuncInterface
{
    public function __invoke(ResponseInterface $rw, RequestInterface $req);
}