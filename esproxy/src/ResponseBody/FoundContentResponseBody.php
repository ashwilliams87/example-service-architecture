<?php

namespace App\ResponseBody;

use App\Contract\DTO\Request\FoundContentDTOInterface;
use App\Contract\DTO\Response\LanDTO;

class FoundContentResponseBody
{

    private FoundContentDTOInterface $dto;
    public function __construct(LanDTO $dto)
    {
        $this->dto = $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->dto->getId(),
            'score' => $this->dto->getScore(),
            'content' => $this->dto->getContent(),
            'metadata' => [
                'book_id' => $this->dto->getBookId(),
                'book_title' => $this->dto->getBookTitle(),
                'author' => $this->dto->getAuthor(),
                'chapter' => $this->dto->getChapter(),
                'page' => $this->dto->getPage(),
            ],
        ];
    }
}