<?php
declare(strict_types=1);


namespace SwooleGin\Exception;


use SwooleGin\Constants\ErrorCode;

class ContainerNotInitializedException extends \Exception
{
    public function __construct(string $message = "Container Not Initalized",)
    {
        parent::__construct($message, ErrorCode::CONTAINER_NOT_INITIALIZED);
    }
}