<?php

namespace App\DTO\Request;

use App\Contract\DTO\Request\DocumentSearchDTOInterface;

class BookSearchDTO implements DocumentSearchDTOInterface
{


    public function __construct(
        private string $searchQuery,
        private int    $limit,
        private int    $subscriberId
    )
    {

    }

    public function getSearchQuery(): string
    {
        return $this->searchQuery;
    }

    public function getSubscriberId(): int
    {
        return $this->subscriberId;
    }

    public function setSubscriberId(int $subscriberId): DocumentSearchDTOInterface
    {
        $this->subscriberId = $subscriberId;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }
}