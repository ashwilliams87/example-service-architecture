<?php

namespace Lan\DataTypes\FileTypes;

use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;

class TextFileType implements FileTypeInterface
{
    private string $name = 'text';

    public function getName(): string
    {
        return $this->name;
    }
}
