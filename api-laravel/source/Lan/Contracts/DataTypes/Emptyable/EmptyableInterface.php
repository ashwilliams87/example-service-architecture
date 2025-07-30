<?php

namespace Lan\Contracts\DataTypes\Emptyable;

interface EmptyableInterface
{
    /**
     * @param null $verifiable
     * @return bool
     */
    public function isEmpty(null $verifiable): bool;
}
