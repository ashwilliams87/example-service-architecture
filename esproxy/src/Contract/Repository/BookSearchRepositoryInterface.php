<?php

namespace App\Contract\Repository;

use App\Contract\DTO\Request\DocumentContentSearchDTOInterface;
use App\Contract\DTO\Request\DocumentSearchDTOInterface;
use Elastica\ResultSet;

interface BookSearchRepositoryInterface
{
    public function searchBooks(DocumentSearchDTOInterface $searchQuery): ResultSet;

    public function searchBookContent(DocumentContentSearchDTOInterface $documentContentSearchDTO): ResultSet;
}