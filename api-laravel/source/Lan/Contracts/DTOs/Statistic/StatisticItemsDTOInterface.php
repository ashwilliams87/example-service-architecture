<?php

namespace Lan\Contracts\DTOs\Statistic;

use Lan\Contracts\DTOs\LanDTOInterface;

interface StatisticItemsDTOInterface extends LanDTOInterface
{
    public function getStatisticItems(): array;
}
