<?php
namespace Lan\Contracts\DTOs\Book;

use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\DataTypes\EbsCarbon;

interface BookCardDTOInterface extends LanDTOInterface, EmptyableInterface, Mobile
{
    public function getId(): int;

    public function getIsbn(): string;

    public function getEdition(): string;

    public function getPages(): int;

    public function isBookPrivate(): bool;

    public function getPublisherId(): int;

    public function getSynthesizerQuality(): int;

    public function getTitle(): string;

    public function getDescription(): string;

    public function getAuthor(): string;

    public function getYear(): int;

    public function isAvailable(): bool;

    public function isHasPdf(): bool;

    public function isHasEpub(): bool;

    public function isHasSyntex(): bool;

    public function isHasAudio(): bool;

    public function getPublisher(): string;

    public function getExpired(): EbsCarbon;

    public function getSynthesizerEditor(): string;

    public function getExpiredDate(): EbsCarbon;

    public function getWord(): string;

    public function getSnippet(): string;

    public function isEmpty(null $verifiable = null): bool;

}
