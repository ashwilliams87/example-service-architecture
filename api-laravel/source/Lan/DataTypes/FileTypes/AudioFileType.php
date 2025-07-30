<?php

namespace Lan\DataTypes\FileTypes;

use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;

class AudioFileType implements FileTypeInterface
{
    private string $name = 'audio';

    public function getName(): string
    {
        return $this->name;
    }
}
