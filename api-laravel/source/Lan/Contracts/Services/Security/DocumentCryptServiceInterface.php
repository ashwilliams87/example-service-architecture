<?php

namespace Lan\Contracts\Services\Security;

use Ebs\Core\Model\Document;
use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;

interface DocumentCryptServiceInterface
{
    public function createKey(Document $document): array;

    public function getMeta(Document $document): string;

    public function getEncryptedFilePath(Document $document, FileTypeInterface $fileType): string;
}
