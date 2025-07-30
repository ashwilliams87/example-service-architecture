<?php

namespace Lan\Contracts\DTOs;

use stdClass;

interface CreatableFromObject
{
    public static function createFromObject(stdClass $object): static;
}
