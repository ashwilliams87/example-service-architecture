<?php

namespace Lan\DataTypes\FileTypes;

use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;

class PdfFileType implements FileTypeInterface
{
    private string $name = 'pdf';

    public function getName(): string
    {
        return $this->name;
    }
}
