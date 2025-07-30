<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Publisher\PublisherListRequest;
use Lan\Contracts\Services\PublisherServiceInterface;
use Lan\Contracts\Services\ApiResponseServiceInterface;

class PublisherController extends EbsController
{
    public function __construct(
        public PublisherServiceInterface $publisherService,
        public ApiResponseServiceInterface $apiResponseService
    )
    {

    }

    public function publisherList(PublisherListRequest $request)
    {
        $listDTO = $this->publisherService->getPublishers($request->toDTO());
        return $this->apiResponseService->makeSuccessResponseWithArray($listDTO->toMobileScheme());
    }
}
