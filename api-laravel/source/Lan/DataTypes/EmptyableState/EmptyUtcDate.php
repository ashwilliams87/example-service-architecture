<?php

namespace Lan\DataTypes\EmptyableState;

use Carbon\Carbon;
use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;

class EmptyUtcDate implements EmptyableInterface
{
    public function isEmpty(?string $dateTimeString): bool
    {
        return $dateTimeString === Carbon::createFromTimestamp(0)->toDateTimeString();
    }
}
