<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Category\CategoryListResponseDTOInterface;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;
use Lan\DTOs\Category\Responses\CategoryDTO;
use Lan\Enums\DocumentType;
use Lan\Repositories\CategoryRepository;
use Lan\Services\CategoryService;

class CategoryServiceTest extends Unit
{
    private CategoryService $categoryService;
    private $categoryRepository;

    protected function _before(): void
    {
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->categoryService = new CategoryService(
            categoryRepository: $this->categoryRepository
        );
    }

    public function testGetBookCategories(): void
    {
        $this->categoryRepository
            ->expects($this->once())
            ->method('getBookCategories')
            ->willReturn($this->prepareFakeCategoryList());

        $offset = 5;
        $limit = 5;

        $expectedServiceResult = [];
        for ($i = $offset + 1; $i <= $offset + $limit; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: 'Category ' . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = $offset + 1; $i <= $offset + $limit; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => 'Category ' . $i,
                "active" => true
            ];
        }

        $result = $this->categoryService->getBookCategories(new DocumentsTypeRequestDTO(
            type: DocumentType::BOOK->value,
            limit: $limit,
            offset: $offset,
            syntex: 1
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }

    public function testGetJournalCategories(): void
    {
        $offset = 5;
        $limit = 5;

        $this->categoryRepository
            ->expects($this->once())
            ->method('getJournalCategories')
            ->willReturn($this->prepareMockCatogoriesQueryResult());

        $expectedServiceResult = [];
        for ($i = $offset + 1; $i <= $offset + $limit; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: 'Category ' . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = $offset + 1; $i <= $offset + $limit; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => 'Category ' . $i,
                "active" => true
            ];
        }

        $result = $this->categoryService->getJournalCategories(new DocumentsTypeRequestDTO(
            type: DocumentType::JOURNAL->value,
            limit: $limit,
            offset: $offset,
            syntex: 1
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }


    public function testGetAllCategories(): void
    {
        $offset = 5;
        $limit = 5;

        $this->categoryRepository
            ->expects($this->once())
            ->method('getAllCategories')
            ->willReturn($this->prepareFakeCategoryList());

        $expectedServiceResult = [];
        for ($i = $offset + 1; $i <= $offset + $limit; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: 'Category ' . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = $offset + 1; $i <= $offset + $limit; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => 'Category ' . $i,
                "active" => true
            ];
        }

        $result = $this->categoryService->getAllCategories(new DocumentsTypeRequestDTO(
            type: 0,
            limit: $limit,
            offset: $offset,
            syntex: 1
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }

    public function testGetSubCategoriesWithPrivateSubcategoriesRequest(): void
    {
        $repositoryResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $repositoryResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "available" => true
            ];
        }

        $categoryRepositoryMock = $this->make(CategoryRepository::class, [
            'getPrivateSubCategories' => Expected::once(function () use ($repositoryResult) {
                return $this->make(QueryResult::class, [
                    'getRows' => Expected::once($repositoryResult),
                ]);
            }),
            'getBookSubCategories' => Expected::never(),
            'getJournalSubCategories' => Expected::never(),
            'getAllSubCategories' => Expected::never(),
        ]);

        $expectedServiceResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: "Test SubCategory Title " . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "active" => true
            ];
        }

        $categoryService = new CategoryService(
            categoryRepository: $categoryRepositoryMock,
        );

        $result = $categoryService->getSubCategories(new DocumentsTypeRequestDTO(
            type: 1,
            categoryId: 0, // при значении 0 должны возвращаться приватные подкатегории
            publisherId: 0,
            limit: 100,
            offset: 1,
            syntex: 1,
            author: '',
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }

    public function testGetSubCategoriesWithBookSubcategoriesSpecifiedInTypeRequest(): void
    {

        $repositoryResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $repositoryResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "available" => true
            ];
        }

        $categoryRepositoryMock = $this->make(CategoryRepository::class, [
            'getBookSubCategories' => Expected::once(function () use ($repositoryResult) {
                return $this->make(QueryResult::class, [
                    'getRows' => Expected::once($repositoryResult),
                ]);
            }),
            'getPrivateSubCategories' => Expected::never(),
            'getJournalSubCategories' => Expected::never(),
            'getAllSubCategories' => Expected::never(),
        ]);


        $expectedServiceResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: "Test SubCategory Title " . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "active" => true
            ];
        }

        $publisherService = new CategoryService(
            categoryRepository: $categoryRepositoryMock,
        );

        $result = $publisherService->getSubCategories(new DocumentsTypeRequestDTO(
            type: 1, // при значении 1 должны возвращаться подкатегории книг
            categoryId: 917,
            publisherId: 0,
            limit: 100,
            offset: 1,
            syntex: 1,
            author: '',
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }

    public function testGetSubCategoriesWithBookSubcategoriesSpecifiedInSyntexRequest(): void
    {
        $repositoryResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $repositoryResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "available" => true
            ];
        }

        $categoryRepositoryMock = $this->make(CategoryRepository::class, [
            'getBookSubCategories' => Expected::once(function () use ($repositoryResult) {
                return $this->make(QueryResult::class, [
                    'getRows' => Expected::once($repositoryResult),
                ]);
            }),
            'getPrivateSubCategories' => Expected::never(),
            'getJournalSubCategories' => Expected::never(),
            'getAllSubCategories' => Expected::never(),
        ]);

        $categoryService = new CategoryService(
            categoryRepository: $categoryRepositoryMock,
        );

        $expectedServiceResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: "Test SubCategory Title " . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "active" => true
            ];
        }

        $result = $categoryService->getSubCategories(new DocumentsTypeRequestDTO(
            type: 0,
            categoryId: 917,
            publisherId: 0,
            limit: 100,
            offset: 1, // при значении 1 должны возвращаться подкатегории книг
            syntex: 1,
            author: '',
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }

    public function testGetSubCategoriesWithJournalSubcategoriesRequest(): void
    {
        $repositoryResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $repositoryResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "available" => true
            ];
        }

        $categoryRepositoryMock = $this->make(CategoryRepository::class, [
            'getJournalSubCategories' => Expected::once(function () use ($repositoryResult) {
                return $this->make(QueryResult::class, [
                    'getRows' => Expected::once($repositoryResult),
                ]);
            }),
            'getPrivateSubCategories' => Expected::never(),
            'getBookSubCategories' => Expected::never(),
            'getAllSubCategories' => Expected::never(),
        ]);

        $expectedServiceResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: "Test SubCategory Title " . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "active" => true
            ];
        }


        $categoryService = new CategoryService(
            categoryRepository: $categoryRepositoryMock,
        );

        $result = $categoryService->getSubCategories(new DocumentsTypeRequestDTO(
            type: 2, // при значении 2 должны возвращаться подкатегории журналов
            categoryId: 917,
            publisherId: 0,
            limit: 100,
            offset: 1,
            syntex: 0,
            author: '',
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }

    public function testGetSubCategoriesWithAllSubcategoriesRequest(): void
    {
        $repositoryResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $repositoryResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "available" => true
            ];
        }

        $categoryRepositoryMock = $this->make(CategoryRepository::class, [
            'getAllSubCategories' => Expected::once(function () use ($repositoryResult) {
                return $this->make(QueryResult::class, [
                    'getRows' => Expected::once($repositoryResult),
                ]);
            }),
            'getPrivateSubCategories' => Expected::never(),
            'getBookSubCategories' => Expected::never(),
            'getJournalSubCategories' => Expected::never(),
        ]);

        $categoryService = new CategoryService(
            categoryRepository: $categoryRepositoryMock,
        );

        $expectedServiceResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedServiceResult[] = new CategoryDTO(
                id: $i,
                title: "Test SubCategory Title " . $i,
                active: true
            );
        }

        $expectedMobileResult = [];
        for ($i = 1; $i <= 50; $i++) {
            $expectedMobileResult[] = [
                "id" => $i,
                "title" => "Test SubCategory Title " . $i,
                "active" => true
            ];
        }

        $result = $categoryService->getSubCategories(new DocumentsTypeRequestDTO(
            type: 0, // при не ожидаемом значении, должны возвращаться все подкатегории
            categoryId: 917,
            publisherId: 0,
            limit: 100,
            offset: 1,
            syntex: 0,
            author: '',
        ));

        $this->assertInstanceOf(CategoryListResponseDTOInterface::class, $result);
        $this->assertEquals($expectedServiceResult, $result->getAll());
        $this->assertEquals($expectedMobileResult, $result->toMobileScheme());
    }

    private function prepareFakeCategoryList(): array
    {
        $data = [];
        for ($i = 1; $i <= 50; $i++) {
            $data[] = (object)[
                'id' => $i,
                'title' => 'Category ' . $i,
            ];
        }
        return $data;
    }

    private function prepareMockCatogoriesQueryResult(): QueryResult
    {
        $data = [];
        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                "all_count" => 298,
                "available" => 1,
                "id" => $i,
                "title" => 'Category ' . $i,
            ];
        }

        $queryResultMock = $this->createMock(QueryResult::class);

        $queryResultMock->method('getRows')->willReturn($data);

        return $queryResultMock;
    }

}
