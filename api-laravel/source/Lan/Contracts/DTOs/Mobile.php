<?php

namespace Lan\Contracts\DTOs;

use Lan\Contracts\Transformers\TransformMobile;

interface Mobile
{
    public function toMobileScheme(TransformMobile $transformer): array;
}
