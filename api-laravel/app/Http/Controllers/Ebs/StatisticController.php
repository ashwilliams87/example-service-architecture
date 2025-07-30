<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Statistic\LogReadStatisticRequest;
use Illuminate\Http\Response;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\StatisticServiceInterface;

class StatisticController extends EbsController
{
    public function __construct(
        protected StatisticServiceInterface $statService,
        protected ApiResponseServiceInterface $apiResponseService
    )
    {

    }

    public function logReadStatistic(LogReadStatisticRequest $request): Response
    {
        $this->statService->logReadStatistic($request->toDTO());
        return $this->apiResponseService->makeEmptySuccessResponse();
    }
}
