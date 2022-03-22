<?php

namespace SwooleGin\Gin\Context;

interface ContextHandlerFuncInterface
{
    public function __invoke(Context $context);
}