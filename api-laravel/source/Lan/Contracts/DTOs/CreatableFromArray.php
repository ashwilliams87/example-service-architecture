<?php

namespace Lan\Contracts\DTOs;

interface CreatableFromArray
{
    public static function createFromArray(array $array): static;
}
