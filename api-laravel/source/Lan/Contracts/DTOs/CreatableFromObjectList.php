<?php

namespace Lan\Contracts\DTOs;

use stdClass;

interface CreatableFromObjectList
{
    /**
     * @param stdClass[] $objectList
     * @return static
     */
    public static function createFromObjectList(array $objectList): static;
}
