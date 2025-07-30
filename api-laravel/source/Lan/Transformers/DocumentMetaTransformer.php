<?php

namespace Lan\Transformers;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Document\DocumentMetaResponseDTO;

class DocumentMetaTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToArray($dto);
    }

    private function convertToArray(DocumentMetaResponseDTO $dto): array
    {
        return [
            'meta' => $dto->getMeta(),
        ];
    }
}
