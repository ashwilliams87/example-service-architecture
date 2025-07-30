<?php

namespace Lan\Contracts\DTOs;

interface CreatableFromIceQueryResultRow
{
    public static function createFromIceQueryResultRow(array $row): static;
}
