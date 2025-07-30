<?php

namespace Lan\Contracts\DTOs;

use Ebs\Core\Model\Document;

interface CreatableFromIceModel
{
    public static function createFromIceModel(Document $model): static;
}
