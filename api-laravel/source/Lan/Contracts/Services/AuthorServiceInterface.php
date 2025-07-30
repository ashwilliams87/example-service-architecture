<?php

namespace Lan\Contracts\Services;

use Lan\Contracts\DTOs\Author\AuthorCharacterMapResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

interface AuthorServiceInterface
{
    public function getAuthorCharacterMap(DocumentsTypeRequestDTOInterface $requestDTO): AuthorCharacterMapResponseDTOInterface;
}
