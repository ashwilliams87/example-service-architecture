<?php

namespace Lan\DataTypes\RequestResult\Error;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\DataTypes\RequestResult\HttpResult;

class MethodNotAllowedError extends HttpResult implements RequestResultInterface
{
    private const MESSAGE = 'Операция не поддерживается';
    private const STATUS_CODE = 405;
    private const IS_ERROR = true;

    public static function create(string $message = self::MESSAGE, int $statusCode = self::STATUS_CODE, bool $isError = self::IS_ERROR): RequestResultInterface
    {
        return new self(message: $message, statusCode: $statusCode, isError: $isError);
    }

    public function __construct(string $message = self::MESSAGE, int $statusCode = self::STATUS_CODE, bool $isError = self::IS_ERROR)
    {
        parent::__construct(message: $message, statusCode: $statusCode, isError: $isError);
    }
}
