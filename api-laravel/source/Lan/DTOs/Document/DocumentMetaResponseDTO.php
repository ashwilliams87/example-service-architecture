<?php

namespace Lan\DTOs\Document;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\Document\DocumentMetaResponseDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\Transformers\DocumentMetaTransformer;

class DocumentMetaResponseDTO implements DocumentMetaResponseDTOInterface, Mobile
{
    private function __construct(
        private readonly string                 $meta,
        private readonly RequestResultInterface $status,
    )
    {

    }

    public static function create(
        string                 $meta = '',
        RequestResultInterface $status = new SuccessOk200(),

    ): static
    {
        return new self(
            meta: $meta,
            status: $status,
        );
    }

    public function getMeta(): string
    {
        return $this->meta;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public function getHttpStatusResult(): RequestResultInterface
    {
        return $this->status;
    }

    public function toMobileScheme(TransformMobile $transformer = new DocumentMetaTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
