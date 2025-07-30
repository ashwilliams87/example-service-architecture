<?php

namespace App\DTO\Response;

use App\Contract\DTO\Response\CollectionDTO;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class FoundDocumentCollectionDTO implements IteratorAggregate, CollectionDTO
{
    /** @var FoundDocumentDTO[] */
    private array $documents;

    public function __construct(array $documents = [])
    {
        $this->documents = array_filter($documents, fn($item) => $item instanceof FoundDocumentDTO);
    }

    public function addDocument($document): void
    {
        $this->documents[] = $document;
    }

    public function getDocuments(): array
    {
        return $this->documents;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->documents);
    }
}