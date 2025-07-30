<?php

namespace Lan\DTOs\Category\Responses;

use Lan\Contracts\DTOs\Category\CategoryDTOInterface;
use Lan\Contracts\DTOs\CreatableFromObject;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Category\CategoryItemTransformer;
use stdClass;

class CategoryDTO implements CategoryDTOInterface, CreatableFromObject, Mobile
{
    public function __construct(
        private readonly int    $id,
        private readonly string $title,
        private readonly bool   $active
    )
    {

    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        return new self(
            id: $row['id'],
            title: $row['title'],
            active: (bool)$row['available'],
        );
    }

    public static function createFromObject(stdClass $object): static
    {
        return new self(
            id: $object->id,
            title: $object->title,
            active: true
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

    public function isActive(): bool
    {
        return $this->active;
    }

    public function toMobileScheme(TransformMobile $transformer = new CategoryItemTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
