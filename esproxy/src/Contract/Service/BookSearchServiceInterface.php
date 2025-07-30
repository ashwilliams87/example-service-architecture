<?php

namespace App\Contract\Service;

use App\Contract\DTO\Request\DocumentContentSearchDTOInterface;
use App\Contract\DTO\Request\DocumentSearchDTOInterface;
use App\Contract\DTO\Request\FoundDocumentDTOInterface;
use App\Contract\DTO\Response\CollectionDTO;

interface BookSearchServiceInterface
{
    /**
     * @param DocumentSearchDTOInterface $documentSearchDTO
     * @return FoundDocumentDTOInterface[]
     */
    public function searchBookCollection(DocumentSearchDTOInterface $documentSearchDTO): CollectionDTO;

    public function searchBookContentCollection(DocumentContentSearchDTOInterface $documentContentSearchDTO): CollectionDTO;
}