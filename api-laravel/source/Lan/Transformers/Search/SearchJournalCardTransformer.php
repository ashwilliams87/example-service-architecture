<?php

namespace Lan\Transformers\Search;

use Lan\Contracts\DTOs\Journal\JournalCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class SearchJournalCardTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(JournalCardDTOInterface $dto): array
    {
        return [
            'word' => $dto->getWord(),
            'snippet' => $dto->getSnippet(),
            'id' => $dto->getId(),
            'publisher' => $dto->getPublisher(),
            'active' => $dto->isAvailable(),
            'title' => $dto->getTitle(),
            'cover' => $dto->getCoverUrl(),
        ];
    }
}
