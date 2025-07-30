<?php

namespace Lan\Services;

use Lan\Contracts\DTOs\Statistic\StatisticItemsDTOInterface;
use Lan\Contracts\Repositories\StatisticRepositoryInterface;
use Lan\Contracts\Services\StatisticServiceInterface;

class StatisticService implements StatisticServiceInterface
{
    public function __construct(
        protected StatisticRepositoryInterface $statisticRepository,
    )
    {

    }

    public function logReadStatistic(StatisticItemsDTOInterface $statisticItemsDTO): void
    {
        $this->statisticRepository->logReadStatisticItemsToAuthenticatedUserSubscriber($statisticItemsDTO);
    }
}
