<?php
declare(strict_types=1);


namespace SwooleGin\Exception;


use Psr\Container\NotFoundExceptionInterface;
use SwooleGin\Constants\ErrorCode;

class ContainerNotFoundException extends \Exception implements NotFoundExceptionInterface
{
    public function __construct(string $id = "")
    {
        parent::__construct("No entry was found for this identifier: $id", ErrorCode::CONTAINER_NOT_FOUND);
    }
}