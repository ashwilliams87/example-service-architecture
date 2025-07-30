<?php

namespace Lan\Transformers\Category;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Category\Responses\CategoryDTO;

class CategoryItemTransformer implements TransformMobile
{

    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToMobileResponseArray($dto);
    }

    private function convertToMobileResponseArray(CategoryDTO $dto)
    {
        return [
            'id' => $dto->getId(),
            'title' => $dto->getTitle(),
            'active' => $dto->isActive(),
        ];
    }
}
