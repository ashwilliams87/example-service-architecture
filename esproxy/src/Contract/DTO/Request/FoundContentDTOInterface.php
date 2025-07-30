<?php

namespace App\Contract\DTO\Request;

use App\Contract\DTO\Response\LanDTO;

interface FoundContentDTOInterface extends LanDTO
{
    public function getId(): string;

    public function getScore(): float;

    public function getContent(): string;
}