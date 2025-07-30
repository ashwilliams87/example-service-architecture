<?php

namespace Lan\DTOs\Book\Responses;

use Ebs\Core\Model\Document;
use Ice\Core\Exception;
use Ice\Exception\Error;
use Ice\Exception\FileNotFound;
use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\Contracts\DTOs\Book\BookCardDTOInterface;
use Lan\Contracts\DTOs\CreatableFromIceModel;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DataTypes\EbsCarbon;
use Lan\DataTypes\EmptyableState\EmptyDTOId;
use Lan\Transformers\Book\BookCardTransformer;

class BookCardDTO implements BookCardDTOInterface, CreatableFromIceModel
{
    private function __construct(
        private readonly mixed                $id,
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

    /**
     * @param Document $model
     * @return static
     * @throws Exception
     * @throws Error
     * @throws FileNotFound
     */
    public static function createFromIceModel(Document $model): static
    {
        return new self(
            id: $model->getPkValue(),
            isbn: $model->get('isbn') ?? '',
            edition: $model->get('edition') ?? '',
            pages: $model->get('pages') ?? 0,
            bookPrivate: (bool)$model->get('book_private'),
            publisherId: $model->get('publisher__fk'),
            synthesizerQuality: (int)$model->get('synthesizer_quality'),
            title: (string)$model->get('book_name'),
            description: $model->get('book_desc') ?? '',
            author: (string)$model->get('authors'),
            year: $model->get('publish_year'),
            available: (bool)$model->get('available'),
            hasPdf: (bool)$model->get('pdf_path'),
            hasEpub: (bool)$model->get('epub_path'),
            hasSyntex: (bool)$model->get('synthesizer_path'),
            hasAudio: (bool)$model->get('audio_path'),
            publisher: (string)$model->get('publisher_name'),
            expired: $model->get('expired') ? EbsCarbon::parse($model->get('expired')) : EbsCarbon::create(),
            synthesizerEditor: (string)$model->get('synthesizer_editor'),
            txt: $model->getRaw('txt', '') ?? '',
            accessDate: $model->getRaw('access_date') ? EbsCarbon::parse($model->get('access_date')) : EbsCarbon::create(),
            expiredDate: $model->getRaw('expired_date') ? EbsCarbon::parse($model->get('expired_date')) : EbsCarbon::create(),
            bookExpired: $model->getRaw('book_expired', '') ? EbsCarbon::parse($model->get('book_expired')) : EbsCarbon::create(),
            isDocumentActive: (bool)$model->getRaw('is_document_active', false),
            word: '',
            snippet: '',
        );
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
            isbn: $row['isbn'] ?? '',
            edition: $row['edition'] ?? '',
            pages: $row['pages'] ?? 0,
            bookPrivate: (bool)$row['book_private'],
            publisherId: 0,
            synthesizerQuality: (int)$row['synthesizer_quality'],
            title: (string)$row['title'],
            description: $row['description'] ?? '',
            author: (string)$row['author'],
            year: (int)$row['year'],
            available: (bool)$row['available'],
            hasPdf: $hasPdf,
            hasEpub: (bool)$row['hasEpub'],
            hasSyntex: (bool)$row['hasSyntex'],
            hasAudio: (bool)$row['hasAudio'],
            publisher: (string)$row['publisher'],
            expired: EbsCarbon::create(),
            synthesizerEditor: (string)$row['synthesizer_editor'],
            txt: $row['txt'] ?? '',
            accessDate: EbsCarbon::create(),
            expiredDate: $row['expired_date'] ? EbsCarbon::parse($row['expired_date']) : EbsCarbon::create(),
            bookExpired: $row['book_expired'] ? EbsCarbon::parse($row['book_expired']) : EbsCarbon::create(),
            isDocumentActive: false,
            word: '',
            snippet:  '',
        );
    }

    public static function create(
        int                $id = 0,
        string             $isbn = '',
        string             $edition = '',
        int                $pages = 0,
        bool               $bookPrivate = false,
        int                $publisherId = 0,
        int                $synthesizerQuality = 0,
        string             $title = '',
        string             $description = '',
        string             $author = '',
        int                $year = 0,
        bool               $available = false,
        bool               $hasPdf = false,
        bool               $hasEpub = false,
        bool               $hasSyntex = false,
        bool               $hasAudio = false,
        string             $publisher = '',
        EbsCarbon          $expired = null,
        string             $synthesizerEditor = '',
        EbsCarbon          $expiredDate = null,
        bool               $isDocumentActive = false,
        string $word = '',
        string $snippet = '',
        EmptyableInterface $emptyableIdState = new EmptyDTOId(),
    ): static
    {

        $expired = $expired ?? EbsCarbon::create();
        $bookExpired = $bookExpired ?? EbsCarbon::create();
        $expiredDate = $expiredDate ?? EbsCarbon::create();
        $emptyableIdState = $emptyableIdState ?? new EmptyDTOId();

        return new self(
            id: $id,
            isbn: $isbn,
            edition: $edition,
            pages: $pages,
            bookPrivate: $bookPrivate,
            publisherId: $publisherId,
            synthesizerQuality: $synthesizerQuality,
            title: $title,
            description: $description,
            author: $author,
            year: $year,
            available: $available,
            hasPdf: $hasPdf,
            hasEpub: $hasEpub,
            hasSyntex: $hasSyntex,
            hasAudio: $hasAudio,
            publisher: $publisher,
            expired: $expired ?? EbsCarbon::create(0),
            synthesizerEditor: $synthesizerEditor,
            txt: '',
            accessDate: $accessDate ?? EbsCarbon::create(0),
            expiredDate: $expiredDate ?? EbsCarbon::create(0),
            bookExpired: $bookExpired ?? EbsCarbon::create(),
            isDocumentActive: $isDocumentActive,
            word: $word,
            snippet: $snippet,
            emptyableIdState: $emptyableIdState,
        //accessId: -1,
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

    public function isAvailable(): bool
    {
        return $this->available;
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

    public function toMobileScheme(TransformMobile $transformer = new BookCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public function getCoverUrl(): string
    {
        // TODO: Implement getCoverUrl() method.
    }
}
