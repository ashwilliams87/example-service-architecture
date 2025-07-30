<?php

namespace Lan\Services\Response;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Lan\Contracts\ApiResponseBuilderInterface;

class ApiResponseBuilder implements ApiResponseBuilderInterface
{
    private Response $response;
    private array $content;

    public function __construct(
        private ResponseFactory $responseFactory,
        protected array $headers = [],
        protected array $error = [],
        protected string $type = 'object',
        protected array $data = [],
        protected int $statusCode = 204,
        protected string $statusName = 'OK',
    )
    {
        $this->makeResponse();
    }

    private function makeResponse(): void
    {
        $this->response = $this->responseFactory->make();
    }

    public function getResponse(): Response
    {
        $this->response->setStatusCode($this->statusCode);
        $this->content = [
            'code'=> $this->statusCode,
            'error'=> $this->error ?: '',
            'status' => $this->statusName,
            'type' => $this->type,
            'data' => $this->data,
        ];

        $this->response->withHeaders(
            array_merge(
                [
                    'Content-Type' => 'application/json',
                    'Charset', 'utf-8'
                ],
                $this->headers ?? []
            )
        );

        $this->response->setContent(json_encode($this->content, JSON_UNESCAPED_UNICODE));

        return $this->response;
    }

    public function reset(): void
    {
        $this->statusCode = 204;
        $this->statusName = 'OK';
        $this->data = [];
        $this->error = [];
        $this->type = 'object';
        $this->headers = [];

        $this->makeResponse();
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setStatusName(string $statusName): self
    {
        $this->statusName = $statusName;
        return $this;
    }

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function addHeader($name, $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function setArrayType(): void
    {
        $this->setType('array');
    }

    public function setNoneType(): void
    {
        $this->setType('none');
    }


    public function setObjectType(): self
    {
        $this->setType('object');
        return $this;
    }

    public function setErrorMessage(
        string $message
    ): self
    {
        $this->error['message'] = $message;

        return $this;
    }

    public function setErrorStatus(): self
    {
        $this->setStatusName('error');
        return $this;
    }

    public function setOkStatus(): void
    {
        $this->setStatusName('OK');
    }
}
