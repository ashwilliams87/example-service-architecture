<?php

namespace Lan\Contracts\Repositories;

use Lan\Contracts\DTOs\Statistic\StatisticItemsDTOInterface;

interface StatisticRepositoryInterface
{
    public function logReadStatisticItemsToAuthenticatedUserSubscriber(StatisticItemsDTOInterface $statisticItemsDTO): void;
}
