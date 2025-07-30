<?php

namespace Lan\Contracts\Services;

use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\MobileListDTOInterface;

interface SearchServiceInterface
{
    public function searchAll(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface;

    public function searchByDocumentType(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface;
}
