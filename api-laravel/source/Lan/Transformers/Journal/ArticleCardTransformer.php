<?php

namespace Lan\Transformers\Journal;

use Ice\Helper\Date;
use Lan\Contracts\DTOs\Journal\ArticleCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class ArticleCardTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(ArticleCardDTOInterface $dto): array
    {
        return [
            'id' => (string) $dto->getId(),
            'all_count' => $dto->getAllCount(),
            'journal_pk' => $dto->getJournalId(),
            'journal_name' => $dto->getJournalName(),
            'access_date' => $dto->getAccessDate()->isEmpty()? $dto->getAccessDate()->toDateTimeString() : null,
            'pages' => $dto->getPageRange(),
            'journalId' => (string) $dto->getJournalId(),
            'journalName' => $dto->getJournalName(),
            'journal_issue_pk' => $dto->getJournalIssuePk(),
            'issue' => (string) $dto->getIssueName(),
            'year' => (string) $dto->getIssueYear(),
            'publisher' => $dto->getPublisher(),
            'title' => $dto->getTitle(),
            'author' => $dto->getAuthor(),
            'start_page' => $dto->getStartPage(),
            'finish_page' => $dto->getFinishPage(),
            'description' => ($dto->getDescription()) ? strip_tags($dto->getDescription()) : '',
            'active' => (Date::get($dto->getAccessDate()->toDateString(), 'Y-m-d H:i:s') > Date::get(null, 'Y-m-d H:i:s')) ? $dto->isAvailable() : false,
        ];
    }
}
