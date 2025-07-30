<?php

namespace Lan\Contracts\DTOs;

interface TransformableToDTO
{
    public function toDTO(): LanDTOInterface;
}
