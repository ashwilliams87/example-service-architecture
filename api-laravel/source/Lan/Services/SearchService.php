<?php

namespace Lan\Services;

use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\MobileListDTOInterface;
use Lan\Contracts\Repositories\SearchRepositoryInterface;
use Lan\Contracts\Services\SearchServiceInterface;
use Lan\DTOs\Search\Responses\SearchArticleCardListResponseDTO;
use Lan\DTOs\Search\Responses\SearchBookCardListResponseResponseDTO;
use Lan\DTOs\Search\Responses\SearchCardListResponseResponseDTO;
use Lan\DTOs\Search\Responses\SearchJournalCardListResponseDTO;

class SearchService implements SearchServiceInterface
{
    public function __construct(
        private SearchRepositoryInterface $searchRepository,
    )
    {

    }

    public function searchAll(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface
    {
        return SearchCardListResponseResponseDTO::createFromArrayList($this->searchRepository->searchAll($requestDTO));
    }

    public function searchByDocumentType(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface
    {
        if ($this->isBookSearchType($requestDTO->getType())) {
            return $this->searchBooks($requestDTO);
        }

        if ($this->isJournalSearchType($requestDTO->getType())) {
            return $this->searchJournals($requestDTO);
        }

        if ($this->isArticleSearchType($requestDTO->getType())) {
            return $this->searchArticles($requestDTO);
        }

        throw new \Exception('Invalid search type'); // todo: реализовать валидацию на стороне Request, чтобы исключить неожидаемые значения
    }

    private function searchBooks(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface
    {
        return SearchBookCardListResponseResponseDTO::createFromArrayList($this->searchRepository->searchBooks($requestDTO));
    }

    private function searchJournals(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface
    {
        return SearchJournalCardListResponseDTO::createFromArrayList($this->searchRepository->searchJournals($requestDTO));
    }

    private function searchArticles(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface
    {
        return SearchArticleCardListResponseDTO::createFromArrayList($this->searchRepository->searchArticles($requestDTO));
    }

    private function isBookSearchType(int $searchType): bool
    {
        return in_array($searchType, SearchRepositoryInterface::BOOK_SEARCH_TYPE_LIST);
    }

    private function isJournalSearchType(int $searchType): bool
    {
        return in_array($searchType, SearchRepositoryInterface::JOURNAL_SEARCH_TYPE_LIST);
    }

    private function isArticleSearchType(int $searchType): bool
    {
        return in_array($searchType, SearchRepositoryInterface::ARTICLE_SEARCH_TYPE_LIST);
    }
}
