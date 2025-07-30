<?php

namespace App\Contract\DTO\Request;

use App\Contract\DTO\Response\LanDTO;

interface FoundDocumentDTOInterface extends LanDTO
{
    public function getId(): int;
    public function getTitle(): string;
    public function getAuthors(): string;
}