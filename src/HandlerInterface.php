<?php

namespace SwooleGin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HandlerInterface
{
    public function ServerHTTP(ResponseInterface $rw, RequestInterface $req): ResponseInterface;
}