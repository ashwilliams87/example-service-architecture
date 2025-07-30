<?php

namespace Lan\Transformers;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Document\DocumentCipherKeyResponseDTO;

class DocumentCipherKeyTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToArray($dto);
    }

    private function convertToArray(DocumentCipherKeyResponseDTO $dto): array
    {
        return [
            'key' => $dto->getKey(),
        ];
    }
}
