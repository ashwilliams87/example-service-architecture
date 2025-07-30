<?php

namespace Lan\Contracts\DataTypes\RequestResult;

interface RequestResultInterface
{
    public function getStatusCode(): int;

    public function getMessage(): string;

    public function isError(): bool;

    public static function create(string $message, int $statusCode, bool $isError): self;

}
