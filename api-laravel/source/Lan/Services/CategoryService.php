<?php

namespace Lan\Services;

use Lan\Contracts\DTOs\Category\CategoryListResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\CategoryRepositoryInterface;
use Lan\Contracts\Services\CategoryServiceInterface;
use Lan\DTOs\Category\Responses\CategoryListResponseDTO;
use Lan\Helpers\DocumentHelper;

class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    )
    {

    }

    public function getBookCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface
    {
        // todo: реализовать кэширование
        $rows = $this->categoryRepository->getBookCategories();

        return CategoryListResponseDTO::createFromObjectList(array_slice($rows, $requestDTO->getOffset(), $requestDTO->getLimit()));
    }

    public function getJournalCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface
    {
        // todo: реализовать кэширование
        $queryResult = $this->categoryRepository->getJournalCategories();

        if ($requestDTO->getOffset() && $requestDTO->getLimit()) {
            $categoryRows = array_slice(
                $queryResult->getRows(),
                $requestDTO->getOffset(),
                $requestDTO->getLimit()
            );
        } else {
            $categoryRows = $queryResult->getRows();
        }

        return CategoryListResponseDTO::createFromIceQueryResultRowList($categoryRows);
    }

    public function getAllCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface
    {
        // todo: реализовать кэширование
        $rows = $this->categoryRepository->getAllCategories();

        if ($requestDTO->getOffset() && $requestDTO->getLimit()) {
            $categories = array_slice($rows, $requestDTO->getOffset(), $requestDTO->getLimit());
        } else {
            $categories = $rows;

        }

        return CategoryListResponseDTO::createFromObjectList($categories);
    }

    public function getSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface
    {
        if ($requestDTO->getCategoryId() === 0) {
            return CategoryListResponseDTO::createFromIceQueryResultRows(
                $this->categoryRepository->getPrivateSubCategories($requestDTO)
            );
        }

        if (DocumentHelper::checkIfDocumentTypeIsBook($requestDTO->getType())
            OR $requestDTO->getSyntex()
        ) {
            return CategoryListResponseDTO::createFromIceQueryResultRows(
                $this->categoryRepository->getBookSubCategories($requestDTO)
            );
        }

        if (DocumentHelper::checkIfDocumentTypeIsJournal($requestDTO->getType()) ) {
            return CategoryListResponseDTO::createFromIceQueryResultRows(
                $this->categoryRepository->getJournalSubCategories($requestDTO)
            );
        }

        return CategoryListResponseDTO::createFromIceQueryResultRows(
            $this->categoryRepository->getAllSubCategories($requestDTO)
        );
    }
}
