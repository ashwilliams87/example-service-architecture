<?php

namespace App\DTO\Request;

use App\Contract\DTO\Request\DocumentContentSearchDTOInterface;

class BookContentSearchDTO implements DocumentContentSearchDTOInterface
{
    private int $subscriberId;

    public function __construct(
        private array  $bookIds,
        private float  $similarityCutoff,
        private string $searchQuery,
        private int    $limit
    )
    {

    }

    public function getBookIds(): array
    {
        return $this->bookIds;
    }

    public function getSimilarityCutoff(): float
    {
        return $this->similarityCutoff;
    }

    public function getSearchQuery(): string
    {
        return $this->searchQuery;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setSubscriberId(int $getSubscriberId): DocumentContentSearchDTOInterface
    {
        $this->subscriberId = $getSubscriberId;
        return $this;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }
}