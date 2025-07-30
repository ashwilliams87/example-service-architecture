<?php

namespace Lan\DataTypes\FileTypes;

use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;

class EpubFileType implements FileTypeInterface
{
    private string $name = 'epub';

    public function getName(): string
    {
        return $this->name;
    }
}
