<?php

namespace Lan\Transformers\Author;

use Lan\Contracts\DTOs\Author\AuthorCharacterMapResponseDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class AuthorCharacterMapTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToArray($dto);
    }

    private function convertToArray(AuthorCharacterMapResponseDTOInterface $dto): array
    {
        $transformationResult = [
            'rus' => [],
            'eng' => [],
            'num' => [],
        ];

        $russianCharacterDTOs = $dto->getRussianCharacterListDTO()->getAll();

        foreach ($russianCharacterDTOs as $characterDTO) {
            $transformationResult['rus'][$characterDTO->getCharacterName()] = $characterDTO->isEnabled();
        }

        $englishCharacterDTOs = $dto->getEnglishCharacterListDTO()->getAll();
        foreach ($englishCharacterDTOs as $characterDTO) {
            $transformationResult['eng'][$characterDTO->getCharacterName()] = $characterDTO->isEnabled();
        }

        $numericCharacterDTOs = $dto->getNumericCharacterListDTO()->getAll();
        foreach ($numericCharacterDTOs as $characterDTO) {
            $transformationResult['num'][$characterDTO->getCharacterName()] = $characterDTO->isEnabled();
        }

        return $transformationResult;
    }
}
