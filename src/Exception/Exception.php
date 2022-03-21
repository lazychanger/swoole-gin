<?php
declare(strict_types=1);


namespace SwooleGin\Exception;


use Throwable;

class Exception extends \Exception implements Throwable
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}