<?php

namespace SwooleGin\Gin;

interface RouteHandleInterface
{
    public function match(string $path): ?Route;
}