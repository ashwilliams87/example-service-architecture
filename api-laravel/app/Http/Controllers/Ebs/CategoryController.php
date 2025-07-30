<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Category\CategoryListRequest;
use App\Http\Requests\Category\SubCategoryListRequest;
use Lan\Contracts\Services\CategoryServiceInterface;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Enums\DocumentType;
use Illuminate\Http\Response;

class CategoryController extends EbsController
{
    public function __construct(
        public CategoryServiceInterface $categoryService,
        public ApiResponseServiceInterface $apiResponseService
    )
    {
    }

    public function categoryList(CategoryListRequest $request): Response
    {
        $requestDTO = $request->toDTO();
        $listDTO = match (DocumentType::tryFrom($requestDTO->getType())) {
            DocumentType::BOOK => $this->categoryService->getBookCategories($requestDTO),
            DocumentType::JOURNAL => $this->categoryService->getJournalCategories($requestDTO),
            default => $this->categoryService->getAllCategories($requestDTO),
        };

        return $this->apiResponseService->makeSuccessResponseWithArray($listDTO);
    }


    public function subCategoryList(SubCategoryListRequest $request): Response
    {
        return $this->apiResponseService->makeSuccessResponseWithArray(
            $this->categoryService->getSubCategories($request->toDTO())
        );
    }
}
