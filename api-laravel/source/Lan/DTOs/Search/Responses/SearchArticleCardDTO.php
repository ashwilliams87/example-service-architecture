<?php

namespace Lan\DTOs\Search\Responses;


use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\Journal\ArticleCardDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DataTypes\EbsCarbon;
use Lan\Transformers\Search\SearchArticleCardTransformer;

class SearchArticleCardDTO implements ArticleCardDTOInterface, CreatableFromIceQueryResultRow
{

    private function __construct(
        private readonly int       $id,
        private readonly string    $title,
        private readonly string    $snippet,
        private readonly bool      $available,
        private readonly int       $startPage,
        private readonly int       $finishPage,
        private readonly int       $pageTotalCount,
        private readonly string    $pageRange,
        private readonly string    $author,
        private readonly string    $publisher,
        private readonly int       $journalId,
        private readonly string    $journalName,
        private readonly string    $issueName,
        private readonly int       $journalIssuePk,
        private readonly int       $issueYear,
        private readonly string    $description,
        private readonly EbsCarbon $accessDate,
        private readonly int       $allCount,
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

    public function getSnippet(): string
    {
        return $this->snippet;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function getStartPage(): int
    {
        return $this->startPage;
    }

    public function getFinishPage(): int
    {
        return $this->finishPage;
    }

    public function getPageTotalCount(): int
    {
        return $this->pageTotalCount;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function getIssueName(): string
    {
        return $this->issueName;
    }

    public function getIssueYear(): int
    {
        return $this->issueYear;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getJournalId(): int
    {
        return $this->journalId;
    }

    public function getJournalName(): string
    {
        return $this->journalName;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public function toMobileScheme(TransformMobile $transformer = new SearchArticleCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        return new static(
            id: $row['id'],
            title: $row['name'],
            snippet: $row['snippet'],
            available: $row['available'],
            startPage: $row['start_page'],
            finishPage: $row['finish_page'],
            pageTotalCount: isset($row['pages']) ? $row['pages'] : 0,
            pageRange: '',
            author: (string)$row['authors'],
            publisher: isset($row['publisher_name']) ? $row['publisher_name'] : '',
            journalId: $row['journal_id'],
            journalName: $row['journal_name'],
            issueName: $row['issue_name'],
            journalIssuePk: isset($row['journal_issue_pk']) ? (int) $row['journal_issue_pk'] : 0,
            issueYear: $row['issue_year'],
            description: isset($row['journal_article_desc']) ? $row['journal_article_desc'] : '',
            accessDate: isset($row['access_date']) ? EbsCarbon::parse($row['access_date']) : EbsCarbon::create(),
            allCount: isset($row['all_count']) ? $row['all_count'] : 0,
        );
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getAccessDate(): EbsCarbon
    {
        return $this->accessDate;
    }

    public function getAllCount(): int
    {
        return $this->allCount;
    }

    public function getPageRange(): string
    {
        return $this->pageRange;
    }

    public function getJournalIssuePk(): int
    {
        return $this->journalIssuePk;
    }

}
