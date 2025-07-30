<?php

namespace Lan\DataTypes\RequestResult\Success;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\DataTypes\RequestResult\HttpResult;

class SuccessOk200 extends HttpResult implements RequestResultInterface
{
    private const MESSAGE = 'Ok';
    private const STATUS_CODE = 200;
    private const IS_ERROR = false;

    public static function create(string $message = self::MESSAGE, int $statusCode = self::STATUS_CODE, bool $isError = self::IS_ERROR): RequestResultInterface
    {
        return parent::create(message: $message, statusCode: $statusCode, isError: $isError);
    }

    public function __construct(string $message = self::MESSAGE, int $statusCode = self::STATUS_CODE, bool $isError = self::IS_ERROR)
    {
        parent::__construct(message: $message, statusCode: $statusCode, isError: $isError);
    }
}
