<?php

namespace Lan\Transformers\Search;

use Lan\Contracts\DTOs\Journal\ArticleCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class SearchArticleCardTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(ArticleCardDTOInterface $dto): array
    {
        return [
            'id' => $dto->getId(),
            'snippet' => $dto->getSnippet(),
            'start_page' => $dto->getStartPage(),
            'finish_page' => $dto->getFinishPage(),
            'pages' => $dto->getPageRange(),
            'active' => $dto->isAvailable(),
            'title' => $dto->getTitle(),
            'author' => $dto->getAuthor(),
            'publisher' => $dto->getPublisher(),
            'journalId' => $dto->getJournalId(),
            'journalName' => $dto->getJournalName(),
            'issue' => $dto->getIssueName(),
            'year' => $dto->getIssueYear(),
            'description' => $dto->getDescription(),
        ];
    }
}
