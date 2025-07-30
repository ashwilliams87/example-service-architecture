<?php

namespace Lan\DTOs\Search\Responses;

use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\Contracts\DTOs\Book\BookCardDTOInterface;
use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DataTypes\EbsCarbon;
use Lan\DataTypes\EmptyableState\EmptyDTOId;
use Lan\Transformers\Search\SearchBookCardTransformer;

class SearchBookCardDTO implements BookCardDTOInterface, CreatableFromIceQueryResultRow
{
    private function __construct(
        private readonly int                $id,
        private readonly string             $isbn,
        private readonly string             $edition,
        private readonly int                $pages,
        private readonly bool               $bookPrivate,
        private readonly int                $publisherId,
        private readonly int                $synthesizerQuality,
        private readonly string             $title,
        private readonly string             $description,
        private readonly string             $author,
        private readonly int                $year,
        private readonly bool               $available,
        private readonly bool               $hasPdf,
        private readonly bool               $hasEpub,
        private readonly bool               $hasSyntex,
        private readonly bool               $hasAudio,
        private readonly string             $publisher,
        private readonly EbsCarbon          $expired,
        private readonly string             $synthesizerEditor,
        private readonly string             $txt,
        private readonly EbsCarbon          $accessDate,
        private readonly EbsCarbon          $expiredDate,
        private readonly EbsCarbon          $bookExpired,
        private readonly bool               $isDocumentActive,
        private readonly string             $word,
        private readonly string             $snippet,
        private readonly EmptyableInterface $emptyableIdState = new EmptyDTOId(),
    )
    {

    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        if (isset($row['hasPdf']) && $row['hasPdf'] && '!' !== substr($row['hasPdf'], 0, 1)) {
            $hasPdf = true;
        } else {
            $hasPdf = false;
        }

        return new static(
            id: $row['id'],
            isbn: isset($row['isbn']) ? $row['isbn'] : '',
            edition: isset($row['edition']) ? $row['edition'] : '',
            pages: isset($row['pages']) ? $row['pages'] : 0,
            bookPrivate: isset($row['book_private']) ? (bool)$row['book_private'] : false,
            publisherId: isset($row['publisher_id']) ? (int)$row['publisher_id'] : 0,
            synthesizerQuality: isset($row['synthesizer_quality']) ? (int)$row['synthesizer_quality'] : 0,
            title: (string)$row['name'],
            description: isset($row['description']) ? $row['description'] : '',
            author: (string)$row['authors'],
            year: isset($row['year']) ? (int)$row['year'] : 0,
            available: (bool)$row['available'],
            hasPdf: $hasPdf,
            hasEpub: isset($row['hasEpub']) ? (bool)$row['hasEpub'] : false,
            hasSyntex: isset($row['hasSyntex']) ? (bool)$row['hasSyntex'] : false,
            hasAudio: isset($row['hasAudio']) ? (bool)$row['hasAudio'] : false,
            publisher: (string)$row['publisher_name'],
            expired: isset($row['expired']) ? EbsCarbon::parse($row['expired']) : EbsCarbon::create(),
            synthesizerEditor:  isset($row['synthesizer_editor']) ? (bool)$row['synthesizer_editor'] : '',
            txt: isset($row['txt']) ? $row['txt'] : '',
            accessDate: isset($row['access_date']) ? EbsCarbon::parse($row['access_date']) : EbsCarbon::create(),
            expiredDate: isset($row['expired_date']) ? EbsCarbon::parse($row['expired_date']) : EbsCarbon::create(),
            bookExpired: isset($row['book_expired']) ? EbsCarbon::parse($row['book_expired']) : EbsCarbon::create(),
            isDocumentActive: isset($row['is_document_active']) ? (bool)$row['is_document_active'] : false,
            word: isset($row['word']) ? (string)$row['word'] : '',
            snippet: isset($row['snippet']) ? (string)$row['snippet'] : '',
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getEdition(): string
    {
        return $this->edition;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function isBookPrivate(): bool
    {
        return $this->bookPrivate;
    }

    public function getPublisherId(): int
    {
        return $this->publisherId;
    }

    public function getSynthesizerQuality(): int
    {
        return $this->synthesizerQuality;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function isHasPdf(): bool
    {
        return $this->hasPdf;
    }

    public function isHasEpub(): bool
    {
        return $this->hasEpub;
    }

    public function isHasSyntex(): bool
    {
        return $this->hasSyntex;
    }

    public function isHasAudio(): bool
    {
        return $this->hasAudio;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getExpired(): EbsCarbon
    {
        return $this->expired;
    }

    public function getSynthesizerEditor(): string
    {
        return $this->synthesizerEditor;
    }

    public function getExpiredDate(): EbsCarbon
    {
        return $this->expiredDate;
    }

    public function isEmpty(null $verifiable = null): bool
    {
        return $this->emptyableIdState->isEmpty($this->getId());
    }

    public function isValid(): bool
    {
        return true;
    }

    public function getAccessDate(): EbsCarbon
    {
        return $this->accessDate;
    }

    public function isDocumentActive()
    {
        return $this->isDocumentActive;
    }

    public function getBookExpired(): EbsCarbon
    {
        return $this->bookExpired;
    }

    public function getTxt(): string
    {
        return $this->txt;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function getSnippet(): string
    {
        return $this->snippet;
    }

    public function toMobileScheme(TransformMobile $transformer = new SearchBookCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }
}
