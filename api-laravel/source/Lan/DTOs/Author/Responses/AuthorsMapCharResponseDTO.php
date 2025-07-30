<?php

namespace Lan\DTOs\Author\Responses;

use Lan\Contracts\DTOs\Author\AuthorCharacterMapResponseDTOInterface;
use Lan\Contracts\DTOs\Collection\LanDTOListInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Author\AuthorCharacterMapTransformer;

class AuthorsMapCharResponseDTO implements AuthorCharacterMapResponseDTOInterface
{
    private function __construct(
        private readonly LanDTOListInterface $russianCharacters,
        private readonly LanDTOListInterface $englishCharacters,
        private readonly LanDTOListInterface $numericCharacters,
    )
    {

    }

    public static function create(
        LanDTOListInterface $russianCharacters,
        LanDTOListInterface $englishCharacters,
        LanDTOListInterface $numericCharacters,
    ): static
    {
        return new self(
            russianCharacters: $russianCharacters,
            englishCharacters: $englishCharacters,
            numericCharacters: $numericCharacters,
        );
    }

    public function getEnglishCharacterListDTO(): LanDTOListInterface
    {
        return $this->englishCharacters;
    }

    public function getRussianCharacterListDTO(): LanDTOListInterface
    {
        return $this->russianCharacters;
    }

    public function getNumericCharacterListDTO(): LanDTOListInterface
    {
        return $this->numericCharacters;
    }

    public function isValid(): bool
    {
        return !empty($this->englishCharacters) && !empty($this->russianCharacters) && !empty($this->numericCharacters);
    }

    public function toMobileScheme(TransformMobile $transformer = new AuthorCharacterMapTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
