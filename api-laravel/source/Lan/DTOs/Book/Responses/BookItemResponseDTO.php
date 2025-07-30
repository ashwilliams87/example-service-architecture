<?php

namespace Lan\DTOs\Book\Responses;

use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\DataTypes\EbsCarbon;
use Lan\DataTypes\EmptyableState\EmptyDTOId;

class BookItemResponseDTO implements LanDTOInterface, CreatableFromIceQueryResultRow, EmptyableInterface
{
    private function __construct(
        private readonly int                $id,
        private readonly bool               $available,
        private readonly bool               $hasEpub,
        private readonly bool               $hasAudio,
        private readonly bool               $hasSyntex,
        private readonly string             $author,
        private readonly string             $title,
        private readonly string             $publisher,
        private readonly string             $synthesizerEditor,
        private readonly int                $synthesizerQuality,
        private readonly bool               $hasPdf,
        private readonly string             $txt,
        private readonly int                $allCount,
        private readonly string             $description,
        private readonly EbsCarbon          $expired,
        private readonly bool               $bookPrivate,
        private readonly int                $year,
        private readonly EbsCarbon          $bookExpired,
        private readonly EbsCarbon          $expiredDate,
        private readonly int                $accessId,

        private readonly EmptyableInterface $emptyableIdState = new EmptyDTOId(),
    )
    {

    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        if ($row['hasPdf'] && '!' !== substr($row['hasPdf'], 0, 1)) {
            $hasPdf = true;
        } else {
            $hasPdf = false;
        }

        return new self(
            id: $row['id'],
            available: (bool)$row['available'],
            hasEpub: (bool)$row['hasEpub'],
            hasAudio: (bool)$row['hasAudio'],
            hasSyntex: (bool)$row['hasSyntex'],
            author: (string)$row['author'],
            title: (string)$row['title'],
            publisher: (string)$row['publisher'],
            synthesizerEditor: (string)$row['synthesizer_editor'],
            synthesizerQuality: (int)$row['synthesizer_quality'],
            hasPdf: $hasPdf,
            txt: $row['txt'] ?? '',
            allCount: (int)$row['all_count'],
            description: $row['description'] ?? '',
            expired: $row['expired'] ? EbsCarbon::parse($row['expired']) : EbsCarbon::create(),
            bookPrivate: (bool)$row['book_private'],
            year: (int)$row['year'],
            bookExpired: $row['book_expired'] ? EbsCarbon::parse($row['book_expired']) : EbsCarbon::create(),
            expiredDate: $row['expired_date'] ? EbsCarbon::parse($row['expired_date']) : EbsCarbon::create(),
            accessId: $row['access_id'] ?? 0,
        );
    }

    public static function create(
        int                $id = 0,
        bool               $available = false,
        bool               $hasEpub = false,
        bool               $hasAudio = false,
        bool               $hasSyntex = false,
        string             $author = '',
        string             $title = '',
        string             $publisher = '',
        string             $synthesizerEditor = '',
        int                $synthesizerQuality = 0,
        bool               $hasPdf = false,
        string             $txt = '',
        int                $allCount = 0,
        string             $description = '',
        EbsCarbon          $expired = null,
        bool               $bookPrivate = false,
        int                $year = 0,
        EbsCarbon          $bookExpired = null,
        EbsCarbon          $expiredDate = null,
        int                $accessId = 0,
        EmptyableInterface $emptyableIdState = null
    ): static
    {
        $expired = $expired ?? EbsCarbon::create();
        $bookExpired = $bookExpired ?? EbsCarbon::create();
        $expiredDate = $expiredDate ?? EbsCarbon::create();
        $emptyableIdState = $emptyableIdState ?? new EmptyDTOId();

        return new self(
            id: $id,
            available: $available,
            hasEpub: $hasEpub,
            hasAudio: $hasAudio,
            hasSyntex: $hasSyntex,
            author: $author,
            title: $title,
            publisher: $publisher,
            synthesizerEditor: $synthesizerEditor,
            synthesizerQuality: $synthesizerQuality,
            hasPdf: $hasPdf,
            txt: $txt,
            allCount: $allCount,
            description: $description,
            expired: $expired,
            bookPrivate: $bookPrivate,
            year: $year,
            bookExpired: $bookExpired,
            expiredDate: $expiredDate,
            accessId: $accessId,
            emptyableIdState: $emptyableIdState
        );
    }

    public static function createFromIceQueryResultRows(mixed $int): void
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

    public function isHasEpub(): bool
    {
        return $this->hasEpub;
    }

    public function isHasAudio(): bool
    {
        return $this->hasAudio;
    }

    public function isHasSyntex(): bool
    {
        return $this->hasSyntex;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getSynthesizerEditor(): string
    {
        return $this->synthesizerEditor;
    }

    public function getSynthesizerQuality(): int
    {
        return $this->synthesizerQuality;
    }

    public function isHasPdf(): bool
    {
        return $this->hasPdf;
    }

    public function getTxt(): string
    {
        return $this->txt;
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getExpired(): EbsCarbon
    {
        return $this->expired;
    }

    public function isBookPrivate(): bool
    {
        return $this->bookPrivate;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getBookExpired(): EbsCarbon
    {
        return $this->bookExpired;
    }

    public function getExpiredDate(): EbsCarbon
    {
        return $this->expiredDate;
    }

    public function getAccessId(): int
    {
        return $this->accessId;
    }

    public function isEmpty(null $verifiable = null): bool
    {
        return $this->emptyableIdState->isEmpty($this->getId());
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
