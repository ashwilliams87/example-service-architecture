<?php

namespace Lan\DTOs\Document;

use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;

class DocumentIdRequestDTO implements DocumentIdRequestDTOInterface
{
    public function __construct(
        private readonly int $id,
    )
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isValid(): bool
    {
        return !empty($this->id);
    }
}
