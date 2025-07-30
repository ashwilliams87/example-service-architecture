<?php

namespace Lan\Services\Response;

use Ice\Exception\Error;
use Illuminate\Http\Response;
use Lan\Contracts\ApiResponseBuilderInterface;
use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\DTOs\MobileResult;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\DataTypes\RequestResult\Error\ResourceNotFoundError;
use Lan\DataTypes\RequestResult\Error\Unauthorized;

class ApiResponseService implements ApiResponseServiceInterface
{
    public function __construct(
        protected ApiResponseBuilderInterface $builder
    )
    {

    }

    public function makeUnauthorizedErrorResponse(RequestResultInterface $error = new Unauthorized()): Response
    {
        return $this->makeErrorResponse($error);
    }

    public function makeSuccessResponseWithObject(Mobile|array $data, int $statusCode = 200): Response
    {
        $this->builder->setStatusCode($statusCode);
        $this->builder->setOkStatus();
        $this->builder->setObjectType();
        $this->builder->setData($this->responseDataToArray($data));

        return $this->builder->getResponse();
    }

    public function makeSuccessResponseWithArray(Mobile|array $data, int $statusCode = 200): Response
    {
        $this->builder->setStatusCode($statusCode);
        $this->builder->setOkStatus();
        $this->builder->setArrayType();
        $this->builder->setData($this->responseDataToArray($data));

        return $this->builder->getResponse();
    }

    public function makeObjectResponseByMobileResult(MobileResult $mobileResult): Response
    {
        if ($mobileResult->getHttpStatusResult()->isError()) {
            return $this->makeErrorResponse($mobileResult->getHttpStatusResult());
        }

        return $this->makeSuccessResponseWithObject($mobileResult->toMobileScheme());
    }

    public function makeEmptyResponseByResult(RequestResultInterface $result): Response
    {
        if ($result->isError()) {
            return $this->makeErrorResponse($result);
        }

        return $this->makeEmptySuccessResponse();
    }

    public function makeEmptySuccessResponse(): Response
    {
        $this->builder->setStatusCode(200);
        $this->builder->setOkStatus();
        $this->builder->setNoneType();

        return $this->builder->getResponse();
    }

    public function makeErrorResponse(RequestResultInterface $error): Response
    {
        $this->builder->reset();
        $this->builder->setStatusCode($error->getStatusCode())
            ->setErrorMessage($error->getMessage())
            ->setErrorStatus()
            ->setObjectType();

        return $this->builder->getResponse();
    }

    public function makeNotFoundResponse(RequestResultInterface $error = new ResourceNotFoundError()): Response
    {
        $this->makeErrorResponse($error);
        return $this->builder->getResponse();
    }

    public function sendEncryptedFileToClient(string $filePath): void
    {
        header("Length-Content: " . filesize($filePath));

        if (ob_get_length()) {
            ob_end_flush();
        }
        $handle = fopen($filePath, 'rb');

        if ($handle) {
            while (!feof($handle)) {
                echo fread($handle, 8192);
            }
            fclose($handle);
        } else {
            $error = true;
        }

        unlink($filePath);

        if (isset($error)) {
            throw new Error('Error while reading encrypted file: ' . $filePath);
        }

        die();
    }

    public function makeErrorResponseWithObject(array $data, RequestResultInterface $error): Response
    {
        $this->builder->reset();
        $this->builder->setStatusCode($error->getStatusCode())
            ->setErrorMessage($error->getMessage())
            ->setErrorStatus()
            ->setObjectType()
            ->setData($data);
        return $this->builder->getResponse();
    }

    private function responseDataToArray(Mobile|array $data): array
    {
        if ($data instanceof Mobile) {
            return $data->toMobileScheme();
        }

        return $data;
    }
}
