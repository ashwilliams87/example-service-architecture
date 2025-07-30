<?php

namespace Lan\DTOs\Publisher\Responses;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Publisher\PublisherItemTransformer;

class PublisherDTO implements LanDTOInterface, CreatableFromIceQueryResultRow, Mobile
{
    private function __construct(
        private readonly int    $id,
        private readonly string $title,
    )
    {

    }

    public static function create(
        int    $id = 0,
        string $title = '',
    ): static
    {
        return new self(
            id: $id,
            title: $title,
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        return new self(id: $row['id'], title: $row['title']);
    }

    public function toMobileScheme(TransformMobile $transformer = new PublisherItemTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
