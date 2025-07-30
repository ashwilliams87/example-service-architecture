<?php

namespace Lan\DTOs\Author\Requests;

use Lan\Contracts\DTOs\Author\AuthorFirstCharRequestDTOInterface;

class AuthorsFirstCharRequestDTO implements AuthorFirstCharRequestDTOInterface
{
    public function __construct(
        private readonly int $categoryId,
        private readonly int $subCategoryId,
        private readonly int $publisherId,
        private readonly int $syntex,
        private readonly int $limit,
        private readonly int $offset,
    )
    {

    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getSubCategoryId(): int
    {
        return $this->subCategoryId;
    }

    public function getPublisherId(): int
    {
        return $this->publisherId;
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
