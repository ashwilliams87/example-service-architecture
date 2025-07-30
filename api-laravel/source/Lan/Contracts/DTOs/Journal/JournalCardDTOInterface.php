<?php

namespace Lan\Contracts\DTOs\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;

interface JournalCardDTOInterface extends LanDTOInterface, Mobile
{
    public function getId(): int;

    public function isAvailable(): bool;

    public function isActive(): bool;

    public function getTitle(): string;

    public function getPublisher(): string;

    public function getAllCount(): int;

    public function getCoverUrl(): string;

    public function getSnippet(): string;

    public function getWord(): string;
}
