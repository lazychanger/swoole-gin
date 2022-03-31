<?php

namespace SwooleGin;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * @param string|class-string $id
     * @param callable|object|class-string $definition
     * @return mixed
     */
    public function set(string $id, callable|object|string $definition);
}