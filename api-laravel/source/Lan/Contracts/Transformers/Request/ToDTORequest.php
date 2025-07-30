<?php

namespace Lan\Contracts\Transformers\Request;

use Lan\Contracts\DTOs\LanDTOInterface;

interface ToDTORequest
{
    public function toDTO(): LanDTOInterface;
}
