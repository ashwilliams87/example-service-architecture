<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Search\SearchAllRequest;
use App\Http\Requests\Search\SearchByDocumentTypeRequest;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\SearchServiceInterface;

class SearchController extends EbsController
{
    public function __construct(
        public SearchServiceInterface   $searchService,
        public ApiResponseServiceInterface $apiResponseService
    )
    {

    }

    public function searchAll(SearchAllRequest $request)
    {
        return $this->apiResponseService->makeSuccessResponseWithObject(
            $this->searchService->searchAll($request->toDTO())
                ->toMobileScheme()
        );
    }

    public function searchByDocumentType(SearchByDocumentTypeRequest $request)
    {
        return $this->apiResponseService->makeSuccessResponseWithArray(
            $this->searchService->searchByDocumentType($request->toDTO())
                ->toMobileScheme()
        );
    }
}
