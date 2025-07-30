<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Lan\Contracts\Repositories\SearchRepositoryInterface;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;
use Lan\DTOs\Search\Responses\SearchArticleCardListResponseDTO;
use Lan\DTOs\Search\Responses\SearchBookCardListResponseResponseDTO;
use Lan\DTOs\Search\Responses\SearchCardListResponseResponseDTO;
use Lan\DTOs\Search\Responses\SearchJournalCardListResponseDTO;
use Lan\Repositories\SearchRepository;
use Lan\Services\SearchService;
use Tests\Support\UnitTester;

class SearchServiceTest extends Unit
{
    protected UnitTester $tester;

    public function testSearchAll(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            categoryId: 1,
            syntex: 1,
            query: 'test'
        );

        $expectedRepositoryResponse = [
            [
                'id' => 2,
                'type' => '1',
                'title' => 'Найдено в названиях книг',
                'count' => 3
            ],
            [
                'id' => 8,
                'type' => '1',
                'title' => 'Найдено в текстах книг',
                'count' => 20
            ]
        ];

        $searchRepositoryMock = $this->make(SearchRepository::class, [
            'searchAll' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) use ($expectedRepositoryResponse){
                return $expectedRepositoryResponse;
            }),
        ]);

        $expectedServiceResult = SearchCardListResponseResponseDTO::createFromArrayList($expectedRepositoryResponse);

        $searchService = new SearchService(
            searchRepository: $searchRepositoryMock,
        );

        $result = $searchService->searchAll($requestDTO);

        $this->assertInstanceOf(SearchCardListResponseResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult->getAll(), $result->getAll());
        $this->assertEquals($expectedServiceResult->getAll()[0], $result->getAll()[0]);
        $this->assertEquals($expectedServiceResult->getAll()[0]->getId(), $result->getAll()[0]->getId());
    }

    public function testSearchBooks(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            type: SearchRepositoryInterface::FOUND_IN_BOOK_TEXT,
            categoryId: 1,
            limit: 50,
            offset: 0,
            syntex: 1,
            query: 'test',
        );

        $expectedRepositoryResponse = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedRepositoryResponse[] = [
                'id' => $i,
                'name' => "Book Name {$i}",
                'word' => "Word {$i}",
                'snippet' => "Snippet {$i}",
                'hasPdf' => true,
                'hasEpub' => false,
                'hasAudio' => true,
                'hasSyntex' => false,
                'book_expired' => null,
                'book_private' => false,
                'synthesizer_editor' => '',
                'synthesizer_quality' => 1,
                'available' => true,
                'authors' => "Author {$i}",
                'publisher_name' => "Publisher {$i}"
            ];
        }

        $searchRepositoryMock = $this->make(SearchRepository::class, [
            'searchBooks' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) use ($expectedRepositoryResponse){
                return $expectedRepositoryResponse;
            }),
        ]);

        $expectedServiceResult = SearchBookCardListResponseResponseDTO::createFromArrayList($expectedRepositoryResponse);

        $searchService = new SearchService(
            searchRepository: $searchRepositoryMock,
        );

        $result = $searchService->searchByDocumentType($requestDTO);

        $this->assertInstanceOf(SearchBookCardListResponseResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult, $result);
    }

    public function testSearchJournals(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            type: SearchRepositoryInterface::FOUND_IN_JOURNAL_TITLES,
            categoryId: 1,
            limit: 50,
            offset: 0,
            syntex: 1,
            query: 'test',
        );

        $repositorySearchResults = [];
        for ($i = 1; $i <= 50; $i++) {
            $repositorySearchResults[] = [
                'id' => $i,
                'cover' => "Cover {$i}",
                'name' => "Journal Name {$i}",
                'word' => "Word {$i}",
                'snippet' => "Snippet {$i}",
                'available' => true,
                'publisher' => "Publisher {$i}",
            ];
        }


        $searchRepositoryMock = $this->make(SearchRepository::class, [
            'searchJournals' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) use ($repositorySearchResults) {
                return $repositorySearchResults;
            }),
        ]);


        $expectedServiceResult = SearchJournalCardListResponseDTO::createFromArrayList($repositorySearchResults);

        $searchService = new SearchService(
            searchRepository: $searchRepositoryMock,
        );

        $result = $searchService->searchByDocumentType($requestDTO);

        $this->assertInstanceOf(SearchJournalCardListResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult, $result);
    }

    public function testSearchArticles(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            type: SearchRepositoryInterface::FOUND_IN_ARTICLE_TITLES,
            categoryId: 1,
            limit: 50,
            offset: 0,
            syntex: 1,
            query: 'test',
        );

        $expectedRepositoryResponse = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedRepositoryResponse[] = [
                'id' => $i,
                'name' => "Article Name {$i}",
                'snippet' => "Snippet {$i}",
                'available' => true,
                'start_page' => 1,
                'finish_page' => 10,
                'pages' => 10,
                'authors' => "Author {$i}",
                'publisher_name' => "Publisher {$i}",
                'journal_id' => $i,
                'journal_name' => "Journal Name {$i}",
                'issue_name' => "Issue Name {$i}",
                'issue_year' => 2024,
                'journal_article_desc' => "Description {$i}",
            ];
        }

        $searchRepositoryMock = $this->make(SearchRepository::class, [
            'searchArticles' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) use ($expectedRepositoryResponse) {
                return $expectedRepositoryResponse;
            }),
        ]);

        $expectedServiceResult = SearchArticleCardListResponseDTO::createFromArrayList($expectedRepositoryResponse);

        $searchService = new SearchService(
            searchRepository: $searchRepositoryMock
        );

        $result = $searchService->searchByDocumentType($requestDTO);

        $this->assertInstanceOf(SearchArticleCardListResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult, $result);
    }
}
