<?php

namespace Lan\Contracts\DTOs\Document;

use Lan\Contracts\DTOs\LanDTOInterface;

interface DocumentsTypeRequestDTOInterface extends LanDTOInterface
{
    public function getType(): int;

    public function getLimit(): int;

    public function getSyntex(): int;

    public function getOffset(): int;

    public function getCategoryId(): int;

    public function getSubCategoryId(): int;

    public function getPublisherId(): int;

    public function getAuthor(): string;

    public function getQuery(): string;
}
