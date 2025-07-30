<?php

namespace Lan\Contracts\DTOs;

interface CreatableFromArrayList
{
    public static function createFromArrayList(array $list): static;
}
