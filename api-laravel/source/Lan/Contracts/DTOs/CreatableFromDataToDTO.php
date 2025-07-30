<?php

namespace Lan\Contracts\DTOs;

interface CreatableFromDataToDTO
{
    public static function create(mixed $data): static;
}
