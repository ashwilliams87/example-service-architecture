<?php

namespace Lan\DTOs\Journal\Responses;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\Journal\ArticleCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DataTypes\EbsCarbon;
use Lan\Transformers\Journal\ArticleCardTransformer;

class ArticleCardDTO implements LanDTOInterface, ArticleCardDTOInterface, CreatableFromIceQueryResultRow
{
    private function __construct(
        private readonly int       $id,
        private readonly bool      $available,
        private readonly int       $journalId,
        private readonly int       $year,
        private readonly string    $description,
        private readonly string    $title,
        private readonly string    $author,
        private readonly string    $pages,
        private readonly EbsCarbon $accessDate,
        private readonly int       $allCount,
        private readonly string    $journalName,
        private readonly int       $journalIssuePk,
        private readonly string    $issueName,
        private readonly string    $publisher,
        private readonly int       $startPage,
        private readonly int       $finishPage,
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

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getIssueYear(): int
    {
        return $this->year;
    }

    public function getJournalId(): int
    {
        return $this->journalId;
    }

    public function getAccessDate(): EbsCarbon
    {
        return $this->accessDate;
    }

    public function getPageRange(): string
    {
        return $this->pages;
    }

    public function getJournalName(): string
    {
        return $this->journalName;
    }

    public function getJournalIssuePk(): int
    {
        return $this->journalIssuePk;
    }

    public function getIssueName(): string
    {
        return $this->issueName;
    }

    public function getStartPage(): int
    {
        return $this->startPage;
    }

    public function getFinishPage(): int
    {
        return $this->finishPage;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }


    public function getPageTotalCount(): int
    {
        return 0;
    }

    public function getSnippet(): string
    {
        return '';
    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        return new static(
            id: $row['id'],
            available: $row['available'],
            journalId: $row['journalId'],
            year: $row['year'],
            description: $row['description'],
            title: (string)$row['title'],
            author: (string)$row['author'],
            pages: $row['pages'],
            accessDate: $row['access_date'] === ''? EbsCarbon::create() : EbsCarbon::parse($row['access_date']),
            allCount: $row['all_count'],
            journalName: $row['journal_name'],
            journalIssuePk: $row['journal_issue_pk'],
            issueName: $row['issue'],
            publisher: $row['publisher'],
            startPage: $row['start_page'],
            finishPage: $row['finish_page'],
        );
    }

    public function toMobileScheme(TransformMobile $transformer = new ArticleCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
