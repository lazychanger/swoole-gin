<?php
declare(strict_types=1);


namespace SwooleGin\Exception;



class NotFoundException extends Exception
{
    public function __construct(string $message = 'Not Found')
    {
        parent::__construct($message, 404);
    }
}