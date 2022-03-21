<?php
declare(strict_types=1);


namespace SwooleGin\Exception;


use SwooleGin\Constants\ErrorCode;

final class FileNotExistsException extends Exception
{
    public function __construct(string $message = 'File not exists')
    {
        parent::__construct($message, ErrorCode::FILE_NOT_EXISTS);
    }
}