<?php

namespace App\Contract\Request;

use App\Contract\DTO\Response\LanDTO;

interface TransformableToDTO
{
    public function toDTO(): LanDTO;
}