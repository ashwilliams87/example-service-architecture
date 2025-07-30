<?php

namespace Lan\DTOs\Journal\Responses;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\Journal\JournalCardDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Journal\JournalCardTransformer;

class JournalCardDTO implements JournalCardDTOInterface, CreatableFromIceQueryResultRow
{
    private function __construct(
        private readonly int    $id,
        private readonly string $title,
        private readonly bool   $available,
        private readonly bool   $active,
        private readonly int    $allCount,
        private readonly string $publisher,
        private readonly string $coverUrl,
    )
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function isValid(): bool
    {
        return true;
    }

    public function getSnippet(): string
    {
        return '';
    }

    public function getWord(): string
    {
        return '';
    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        return new static(
            id: $row['id'],
            title: (string)$row['title'],
            available: (bool)$row['available'],
            active: (string)$row['active'],
            allCount: (int)$row['all_count'],
            publisher: (string)$row['publisher'],
            coverUrl: (string)$row['cover'],
        );
    }

    public function toMobileScheme(TransformMobile $transformer = new JournalCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
