<?php

namespace Lan\DataTypes\EmptyableState;

use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;

class EmptyDTOId implements EmptyableInterface
{
    public function isEmpty(?int $id): bool
    {
        return $id < 1;
    }
}
