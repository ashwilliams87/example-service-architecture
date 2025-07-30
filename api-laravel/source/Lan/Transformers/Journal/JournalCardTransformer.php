<?php
namespace Lan\Transformers\Journal;

use Lan\Contracts\DTOs\Journal\JournalCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class JournalCardTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(JournalCardDTOInterface $dto): array
    {
        return [
            'id' => $dto->getId(),
            'title' => $dto->getTitle(),
            'publisher' => $dto->getPublisher(),
            'cover' => $dto->getCoverUrl(),
            'active' => $dto->isActive(),
            'all_count' => $dto->getAllCount(),
        ];
    }
}
