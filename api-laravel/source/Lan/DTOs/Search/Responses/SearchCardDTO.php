<?php

namespace Lan\DTOs\Search\Responses;

use Lan\Contracts\DTOs\Search\SearchCardDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Search\SearchCardTransformer;

class SearchCardDTO implements SearchCardDTOInterface
{
    public function __construct(
        private readonly int    $id,
        private readonly string $type,
        private readonly string $title,
        private readonly int    $count,
    )
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        return new static(
            id: $row['id'],
            type: $row['type'],
            title: $row['title'],
            count: $row['count'],
        );
    }

    public function toMobileScheme(TransformMobile $transformer = new SearchCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
