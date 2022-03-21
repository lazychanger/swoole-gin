<?php
declare(strict_types=1);


namespace SwooleGin\Logger;


use SwooleGin\Constants\ErrorCode;
use SwooleGin\Exception\Exception;

final class UnknownLevelException extends Exception
{
    public function __construct(string $message = "unknown level")
    {
        parent::__construct($message, ErrorCode::UNKNOWN_LOGGER_LEVEL);
    }
}