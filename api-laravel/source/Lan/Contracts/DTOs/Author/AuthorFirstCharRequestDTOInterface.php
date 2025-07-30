<?php

namespace Lan\Contracts\DTOs\Author;


use Lan\Contracts\DTOs\LanDTOInterface;

interface AuthorFirstCharRequestDTOInterface extends LanDTOInterface
{
    public function getCategoryId(): int;

    public function getSubCategoryId(): int;


    public function getPublisherId(): int;

    public function getSyntex(): int;

    public function getLimit(): int;

    public function getOffset(): int;
}
