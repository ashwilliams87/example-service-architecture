<?php

namespace Lan\Contracts\Repositories;

use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

interface AuthorRepositoryInterface
{
    public function getAllCharacters(): array;

    public function getCharacterMap(): array;

    public function getAuthorCharacterList(DocumentsTypeRequestDTOInterface $requestDTO): array;

    public function getAuthorPrivateCharacterList(DocumentsTypeRequestDTOInterface $requestDTO): array;
}
