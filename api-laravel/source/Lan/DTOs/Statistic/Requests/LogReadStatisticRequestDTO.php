<?php

namespace Lan\DTOs\Statistic\Requests;


use Lan\Contracts\DTOs\Statistic\StatisticItemsDTOInterface;

readonly class LogReadStatisticRequestDTO implements StatisticItemsDTOInterface
{
    public function __construct(
        private array $statisticItems,
    )
    {

    }

    public function getStatisticItems(): array
    {
        return $this->statisticItems;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
