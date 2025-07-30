<?php

namespace Lan\Contracts\Services;

use Illuminate\Http\Response;
use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\DTOs\MobileResult;

interface ApiResponseServiceInterface
{
    public function makeUnauthorizedErrorResponse(RequestResultInterface $error): Response;

    public function makeSuccessResponseWithObject(Mobile|array $data, int $statusCode = 200): Response;

    public function makeSuccessResponseWithArray(Mobile|array $data, int $statusCode = 200): Response;

    public function makeObjectResponseByMobileResult(MobileResult $mobileResult): Response;

    public function makeEmptyResponseByResult(RequestResultInterface $result): Response;

    public function makeEmptySuccessResponse(): Response;

    public function makeErrorResponse(RequestResultInterface $error): Response;

    public function makeErrorResponseWithObject(array $data, RequestResultInterface $error): Response;

    public function makeNotFoundResponse(RequestResultInterface $error): Response;

    public function sendEncryptedFileToClient(string $filePath): void;
}
