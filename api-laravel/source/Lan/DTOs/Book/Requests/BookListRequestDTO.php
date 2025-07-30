<?php

namespace Lan\DTOs\Book\Requests;


use Lan\Contracts\DTOs\Book\BookListRequestDTOInterface;

class BookListRequestDTO implements BookListRequestDTOInterface
{
    public function __construct(
        private readonly int    $category,
        private readonly int    $subCategory,
        private readonly int    $publisher,
        private readonly string $author,
        private readonly string $sortingField,
        private readonly int    $syntex,
        private readonly int    $limit,
        private readonly int    $offset,
    )
    {

    }

    public function getCategory(): int
    {
        return $this->category;
    }

    public function getSubCategory(): int
    {
        return $this->subCategory;
    }

    public function getPublisher(): int
    {
        return $this->publisher;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getSortingField(): string
    {
        return $this->sortingField;
    }

    public function getSyntex(): int
    {
        return $this->syntex;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
