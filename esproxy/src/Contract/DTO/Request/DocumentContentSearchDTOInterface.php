<?php

namespace App\Contract\DTO\Request;

use App\Contract\DTO\Response\LanDTO;

interface DocumentContentSearchDTOInterface extends LanDTO
{
    public function getBookIds(): array;

    public function getSimilarityCutoff(): float;

    public function getSearchQuery(): string;

    public function getLimit(): int;

    public function setSubscriberId(int $getSubscriberId): DocumentContentSearchDTOInterface;
}