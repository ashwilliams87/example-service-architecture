<?php

namespace Lan\DTOs\Search\Responses;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\Journal\JournalCardDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Search\SearchJournalCardTransformer;

class SearchJournalCardDTO implements JournalCardDTOInterface, CreatableFromIceQueryResultRow
{
    private function __construct(
        private readonly int    $id,
        private readonly string $title,
        private readonly string $word,
        private readonly string $snippet,
        private readonly bool   $available,
        private readonly bool   $active,
        private readonly string $publisher,
        private readonly string $coverUrl,
        private readonly int $allCount,
    )
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function getSnippet(): string
    {
        return $this->snippet;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }


    public static function createFromIceQueryResultRow(array $row): static
    {
        return new static(
            id: $row['id'],
            title: $row['name'],
            word: $row['word'],
            snippet: $row['snippet'],
            available: $row['available'],
            active: false,
            publisher: $row['publisher'],
            coverUrl: $row['cover'],
            allCount: isset($row['all_count']) ? (int) $row['all_count'] : 0,
        );
    }

    public function toMobileScheme(TransformMobile $transformer = new SearchJournalCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }
}
