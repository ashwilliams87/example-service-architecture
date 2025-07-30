<?php

namespace App\Contract\DTO\Request;

use App\Contract\DTO\Response\LanDTO;

interface DocumentSearchDTOInterface extends LanDTO
{
    public function getSearchQuery(): string;

    public function getSubscriberId(): int;

    public function setSubscriberId(int $subscriberId): DocumentSearchDTOInterface;

    public function getLimit();
}