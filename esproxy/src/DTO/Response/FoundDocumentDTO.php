<?php

namespace App\DTO\Response;

use App\Contract\DTO\Request\FoundDocumentDTOInterface;

class FoundDocumentDTO implements FoundDocumentDTOInterface
{
    public function __construct(
        private int    $id,
        private string $title,
        private string $year,
        private string $authors,
    )
    {

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }


    public function getYear(): string
    {
        return $this->year;
    }

    public function getAuthors(): string
    {
        return $this->authors;
    }
}