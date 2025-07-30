<?php

namespace Lan\Contracts\DTOs;

interface CreatableFromIceQueryResultRowList
{
    public static function createFromIceQueryResultRowList(array $rows): static;
}
