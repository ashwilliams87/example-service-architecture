<?php

namespace App\DTO\Response;

use App\Contract\DTO\Request\FoundContentDTOInterface;

class FoundContentDTO implements FoundContentDTOInterface
{
    public function __construct(
        private string $id,
        private float  $score,
        private string $content,
        private array $metadata
    )
    {

    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

}