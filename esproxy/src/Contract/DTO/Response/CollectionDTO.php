<?php

namespace App\Contract\DTO\Response;

use App\DTO\Response\FoundDocumentDTO;
use IteratorAggregate;
use Traversable;

interface CollectionDTO extends IteratorAggregate
{
    public function addDocument($document): void;

    public function getDocuments(): array;

    public function getIterator(): Traversable;
}
