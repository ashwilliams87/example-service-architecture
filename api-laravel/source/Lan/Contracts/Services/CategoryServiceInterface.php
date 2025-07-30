<?php

namespace Lan\Contracts\Services;

use Lan\Contracts\DTOs\Category\CategoryListResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

interface CategoryServiceInterface
{
    public function getBookCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface;

    public function getJournalCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface;

    public function getAllCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface;

    public function getSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): CategoryListResponseDTOInterface;
}
