<?php

namespace Lan\DataTypes\RequestResult;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;

class HttpResult implements RequestResultInterface
{

    function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isError(): bool
    {
        return $this->isError;
    }

    protected function __construct(protected readonly string $message, protected readonly int $statusCode, protected readonly bool $isError = false)
    {

    }

    public static function create(string $message = 'I’m a teapot, but programmer are lame.', int $statusCode = 418, bool $isError = true): RequestResultInterface
    {
        return new static($message, $statusCode, $isError);
    }
}
