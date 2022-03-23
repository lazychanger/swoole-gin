<?php
declare(strict_types=1);


namespace SwooleGin;


use Psr\Http\Message\ResponseInterface;
use SwooleGin\Utils\HTTPStatus;

class Response implements ResponseInterface
{
    use MessageTrait;

    protected int $statusCode = HTTPStatus::StatusOK;
    protected string $reasonPhrase = 'OK';
    protected string $version = '1.1';


    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): Response|static
    {
        $this->statusCode = $code;
        !empty($reasonPhrase) && $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

}