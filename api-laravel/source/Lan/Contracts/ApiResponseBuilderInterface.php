<?php

namespace Lan\Contracts;

use Illuminate\Http\Response;

interface ApiResponseBuilderInterface
{
    public function getResponse(): Response;

    public function reset(): void;

    public function setStatusCode(int $statusCode): self;

    public function setStatusName(string $statusName): self;

    public function setData($data): self;

    public function addHeader($name, $value): self;

    public function setType(string $type): self;

    public function setErrorMessage(string $message): self;

    public function setErrorStatus(): self;

    public function setObjectType(): self;

    public function setOkStatus(): void;

    public function setNoneType(): void;

    public function setArrayType(): void;
}
