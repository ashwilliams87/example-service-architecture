<?php

namespace Lan\Contracts\Services;
use Lan\Contracts\DTOs\Statistic\StatisticItemsDTOInterface;

interface StatisticServiceInterface
{
    public function logReadStatistic(StatisticItemsDTOInterface $statisticItemsDTO): void;
}
