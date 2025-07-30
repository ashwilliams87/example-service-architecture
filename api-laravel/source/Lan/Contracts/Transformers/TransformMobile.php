<?php

namespace Lan\Contracts\Transformers;

use Lan\Contracts\DTOs\LanDTOInterface;

interface TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array;
}
