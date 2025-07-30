<?php

namespace App\DTO\Response;

use App\Contract\DTO\Response\CollectionDTO;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class FoundDocumentContentCollectionDTO implements IteratorAggregate, CollectionDTO
{
    /** @var FoundContentDTO[] */
    private array $documents;

    public function __construct(array $documents = [])
    {
        $this->documents = array_filter($documents, fn($item) => $item instanceof FoundContentDTO);
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