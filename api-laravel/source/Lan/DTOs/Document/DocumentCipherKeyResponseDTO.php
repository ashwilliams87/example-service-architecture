<?php

namespace Lan\DTOs\Document;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\Document\DocumentCipherKeyResponseDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\Transformers\DocumentCipherKeyTransformer;

class DocumentCipherKeyResponseDTO implements DocumentCipherKeyResponseDTOInterface, Mobile
{
    private function __construct(
        private readonly array                  $key,
        private readonly RequestResultInterface $status,
    )
    {

    }

    public static function create(
        array                  $key = [],
        RequestResultInterface $status = new SuccessOk200(),
    ): static
    {
        return new self(
            key: $key,
            status: $status,
        );
    }

    public function getKey(): array
    {
        return $this->key;
    }

    public function isValid(): bool
    {
        return throw new \Exception('asd');
    }

    public function getHttpStatusResult(): RequestResultInterface
    {
        return $this->status;
    }

    public function toMobileScheme(TransformMobile $transformer = new DocumentCipherKeyTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
