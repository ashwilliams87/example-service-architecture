<?php

namespace Lan\Contracts\DTOs\Book;

use Lan\Contracts\DTOs\LanDTOInterface;

interface BookListRequestDTOInterface extends LanDTOInterface
{
    public function getCategory(): int;

    public function getSubCategory(): int;

    public function getPublisher(): int;

    public function getAuthor(): string;

    public function getSortingField(): string;

    public function getSyntex(): int;

    public function getLimit(): int;

    public function getOffset(): int;
}
