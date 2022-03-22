<?php
declare(strict_types=1);


namespace SwooleGin\Exception;


use SwooleGin\Utils\HTTPStatus;

class HTTPException extends Exception
{
    public function __construct(int $code = 0)
    {
        parent::__construct(HTTPStatus::statusText($code), $code);
    }
}