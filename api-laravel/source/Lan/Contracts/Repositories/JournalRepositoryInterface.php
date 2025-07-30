<?php

namespace Lan\Contracts\Repositories;

use Ebs\Model\Journal;
use Ebs\Model\Journal_Article;
use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

interface JournalRepositoryInterface
{
    public function getJournalsFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;
    public function getJournalsWithCoverLinksFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): array;

    public function getArticlesFromJournal(DocumentIdRequestDTOInterface $requestDTO): array;

    public function getJournal(int $journalId): Journal;

    public function getArticle(int $articleId): Journal_Article;

    public function getJournalWithIssues(int $journalId): array;

    public function getJournalWithIssuesWithCover(int $journalId): array;

    public function addCoverLinksToJournalRows(array $journalRows): array;
}
