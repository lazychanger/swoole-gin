<?php

namespace SwooleGin\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
    public function handle(ResponseInterface $rw, RequestInterface $req, callable $next);
}