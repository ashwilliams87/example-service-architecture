<?php

namespace Lan\Contracts\DTOs\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\DataTypes\EbsCarbon;

interface ArticleCardDTOInterface extends LanDTOInterface, Mobile
{
    public function getId(): int;

    public function isAvailable(): bool;

    public function getAuthor(): string;

    public function getTitle(): string;

    public function getPublisher(): string;

    public function getAllCount(): int;

    public function getDescription(): string;

    public function getIssueYear(): int;

    public function getJournalId(): int;

    public function getAccessDate(): EbsCarbon;

    public function getPageRange(): string;
    public function getPageTotalCount(): int;

    public function getJournalName(): string;

    public function getJournalIssuePk(): int;

    public function getIssueName(): string;

    public function getStartPage(): int;

    public function getFinishPage(): int;

    public function getSnippet(): string;
}
