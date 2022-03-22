<?php
declare(strict_types=1);


namespace SwooleGin\Exception;


use SwooleGin\Utils\HTTPStatus;

class NotFoundException extends Exception
{
    public function __construct(string $message = '')
    {
        $message = empty($message) ? HTTPStatus::statusText(HTTPStatus::StatusOK) : $message;
        parent::__construct($message, HTTPStatus::StatusOK);
    }
}