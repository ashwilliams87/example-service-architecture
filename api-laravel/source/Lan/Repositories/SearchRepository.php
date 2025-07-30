<?php

namespace Lan\Repositories;

use Ebs\Helper\Search;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\JournalRepositoryInterface;
use Lan\Contracts\Repositories\SearchRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;

class SearchRepository implements SearchRepositoryInterface
{

    public function __construct(
        private SecurityServiceInterface   $securityService,
        private JournalRepositoryInterface $journalRepository,
    )
    {

    }

    public function searchAll(DocumentsTypeRequestDTOInterface $requestDTO): array
    {
        $input = [
            'subscriber' => $this->securityService->getSubscriber(),
            'user' => $this->securityService->getUser(),
            'query' => $requestDTO->getQuery(),
            'category' => $requestDTO->getCategoryId(),
            'syntex' => $requestDTO->getSyntex(),
            'platforms' => [2],
            'limit' => 1
        ];

        $searchResults = [];

        $input['type'] = 'main';
        $searchResult = Search::book($input);

        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_BOOK_TITLES, $searchResult);
        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_BOOK_AUTHORS, $searchResult);
        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_BOOKS, $searchResult);

        $input['type'] = 'text';
        $searchResult = Search::book($input);

        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_BOOK_TEXT, $searchResult);

        $input['type'] = 'name';
        $searchResult = Search::journal($input);

        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_JOURNAL_TITLES, $searchResult);

        $input['type'] = 'name';
        $searchResult = Search::journalArticle($input);

        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_ARTICLE_TITLES, $searchResult);

        $input['type'] = 'author';
        $searchResult = Search::journalArticle($input);

        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_ARTICLE_AUTHORS, $searchResult);

        $input['type'] = 'keyword';
        $searchResult = Search::journalArticle($input);

        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_ARTICLES_BY_KEYWORDS, $searchResult);

        $input['type'] = 'text';
        $searchResult = Search::journalArticle($input);

        $searchResults[] = $this->extractSearchResult(self::FOUND_IN_ARTICLE_TEXTS, $searchResult);

        usort($searchResults, function ($a, $b) {
            if ((int)$a['id'] === (int)$b['id']) {
                return 0;
            } else {
                return ($a['id'] > $b['id'] ? 1 : -1);
            }
        });

        return $searchResults;
    }

    private function extractSearchResult(int $searchTypeId, array $searchResult): array
    {
        $field = $this->getSearchTypeField($searchTypeId);

        return [
            'id' => $searchTypeId,
            'type' => $this->getSearchTypeEntity($searchTypeId),
            'title' => $this->getSearchTypeName($searchTypeId),
            'count' => $searchResult[$field]['total'] ?? 0,
        ];
    }

    public function searchBooks(DocumentsTypeRequestDTOInterface $requestDTO): array
    {
        $input = $this->prepareSearchByDocumentTypeInput($requestDTO);
        $field = $this->getSearchTypeField($requestDTO->getType());
        return Search::book($input)[$field]['items'];
    }

    public function searchJournals(DocumentsTypeRequestDTOInterface $requestDTO): array
    {
        $input = $this->prepareSearchByDocumentTypeInput($requestDTO);
        $field = $this->getSearchTypeField($requestDTO->getType());
        return $this->journalRepository->addCoverLinksToJournalRows(Search::journal($input)[$field]['items']);
    }

    public function searchArticles(DocumentsTypeRequestDTOInterface $requestDTO): array
    {
        $input = $this->prepareSearchByDocumentTypeInput($requestDTO);
        $field = $this->getSearchTypeField($requestDTO->getType());
        return Search::journalArticle($input)[$field]['items'];
    }

    private function prepareSearchByDocumentTypeInput(DocumentsTypeRequestDTOInterface $requestDTO): array
    {
        return [
            'subscriber' => $this->securityService->getSubscriber(),
            'user' => $this->securityService->getUser(),
            'query' => $requestDTO->getQuery(),
            'category' => $requestDTO->getCategoryId(),
            'syntex' => $requestDTO->getSyntex(),
            'platforms' => [2],
            'limit' => $requestDTO->getLimit(),
            'page' => ($requestDTO->getOffset() + $requestDTO->getLimit()) / $requestDTO->getLimit(),
            'type' => $this->getSearchTypeField($requestDTO->getType()),
        ];
    }

    private function getSearchTypeField(int $searchTypeId): string
    {
        return self::SEARCH_TYPE_TO_RESULTS_TYPE[$searchTypeId]['field'];
    }

    private function getSearchTypeEntity(int $searchTypeId): string
    {
        return self::SEARCH_TYPE_TO_RESULTS_TYPE[$searchTypeId]['entity'];
    }

    private function getSearchTypeName(int $searchTypeId): string
    {
        return self::SEARCH_TYPE_TO_RESULTS_TYPE[$searchTypeId]['name'];
    }
}
