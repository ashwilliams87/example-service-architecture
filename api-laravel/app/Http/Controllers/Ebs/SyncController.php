<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Sync\SyncAllRequest;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\SyncServiceInterface;

class SyncController extends EbsController
{
    public function __construct(
        public SyncServiceInterface     $syncService,
        public ApiResponseServiceInterface $apiResponseService
    )
    {
    }

    public function syncAll(SyncAllRequest $request)
    {
        return $this->apiResponseService->makeSuccessResponseWithArray(
            $this->syncService->syncBookmarkAndFavorites($request->toDTO())
                ->toMobileScheme()
        );
    }
}
