<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;
use Lan\DTOs\Publisher\Responses\PublisherDTO;
use Lan\DTOs\Publisher\Responses\PublisherListResponseResponseDTO;
use Lan\Repositories\PublisherRepository;
use Lan\Services\PublisherService;
use Tests\Support\UnitTester;

class PublisherServiceTest extends Unit
{
    protected UnitTester $tester;

    public function testGetNonPrivatePublisherList(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            type: 1,
            categoryId: 917,
            subCategoryId: 0,
            limit: 100,
            offset: 1,
            syntex: 1,
            author: ''
        );

        $mockRows = [];
        for ($i = 1; $i <= 50; $i++) {
            $mockRows[] = [
                "id" => $i,
                "title" => "Test Publisher Title " . $i,
            ];
        }

        $repositoryQueryResultMock = $this->make(QueryResult::class, [
            'getRows' => Expected::once(function () use ($mockRows) {
                return $mockRows;
            }),
        ]);

        $expectedQueryResultMock = $this->make(QueryResult::class, [
            'getRows' => Expected::once(function () use ($mockRows) {
                return $mockRows;
            }),
        ]);

        $publisherRepositoryMock = $this->make(PublisherRepository::class, [
            'getPublisherList' => Expected::once(function (DocumentsTypeRequestDTOInterface $requestDTO) use ($repositoryQueryResultMock) {
                self::assertInstanceOf(DocumentsTypeRequestDTOInterface::class, $requestDTO);
                return $repositoryQueryResultMock;
            }),
            'getPrivatePublisherList' => Expected::never(),
        ]);

        $expectedServiceResult = PublisherListResponseResponseDTO::createFromIceQueryResultRows($expectedQueryResultMock);

        $publisherService = new PublisherService(
            publisherRepository: $publisherRepositoryMock,
        );

        $result = $publisherService->getPublishers($requestDTO);

        $this->assertInstanceOf(PublisherListResponseResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult, $result);
    }

    public function testGetPrivatePublisherList(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            type: 1,
            categoryId: 0,
            subCategoryId: 0,
            limit: 100,
            offset: 1,
            syntex: 1,
            author: ''
        );

        $mockRows = [];
        for ($i = 1; $i <= 50; $i++) {
            $mockRows[] = [
                "id" => $i,
                "title" => "Test Publisher Title " . $i,
            ];
        }

        $repositoryQueryResultMock = $this->make(QueryResult::class, [
            'getRows' => Expected::once(function () use ($mockRows) {
                return $mockRows;
            }),
        ]);

        $expectedQueryResultMock = $this->make(QueryResult::class, [
            'getRows' => Expected::once(function () use ($mockRows) {
                return $mockRows;
            }),
        ]);

        $publisherRepositoryMock = $this->make(PublisherRepository::class, [
            'getPublisherList' => Expected::never(),
            'getPrivatePublisherList' => Expected::once(function (DocumentsTypeRequestDTOInterface $requestDTO) use ($repositoryQueryResultMock) {
                self::assertInstanceOf(DocumentsTypeRequestDTOInterface::class, $requestDTO);
                return $repositoryQueryResultMock;
            }),
        ]);

        $expectedDTOs = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedDTOs[] = PublisherDTO::create(
                id: $i,
                title: "Test Publisher Title " . $i,
            );
        }

        $expectedServiceResult = PublisherListResponseResponseDTO::createFromIceQueryResultRows($expectedQueryResultMock);

        $publisherService = new PublisherService(
            publisherRepository: $publisherRepositoryMock,
        );

        $result = $publisherService->getPublishers($requestDTO);

        $this->assertInstanceOf(PublisherListResponseResponseDTO::class, $result);
        $this->assertEquals($expectedServiceResult, $result);
    }
}
