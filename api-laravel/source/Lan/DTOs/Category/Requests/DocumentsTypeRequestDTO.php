<?php

namespace Lan\DTOs\Category\Requests;

use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

class DocumentsTypeRequestDTO implements DocumentsTypeRequestDTOInterface
{
    /**
     * @param int $type
     * @param int $categoryId
     * @param int $subCategoryId
     * @param int $publisherId
     * @param int $limit
     * @param int $offset
     * @param int $syntex
     * @param string $author
     * @param string $sortingField
     * @param string $query
     */
    public function __construct(
        private readonly int    $type = -1,
        private readonly int    $categoryId = -1,
        private readonly int    $subCategoryId = -1,
        private readonly int    $publisherId = -1,
        private readonly int    $limit = 0,
        private readonly int    $offset = 0,
        private readonly int    $syntex = 0,
        private readonly string $author = '',
        private readonly string $sortingField = '',
        private readonly string $query = '',
    )
    {

    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getSyntex(): int
    {
        return $this->syntex;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getPublisherId(): int
    {
        return $this->publisherId;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public function getSubCategoryId(): int
    {
        return $this->subCategoryId;
    }

    public function getSortingField(): string
    {
        return $this->sortingField;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
