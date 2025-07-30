<?php

namespace Lan\DTOs\Author\Responses;

use Lan\Contracts\DTOs\LanDTOInterface;

class AuthorFirstCharDTO implements LanDTOInterface
{
    private function __construct(
        private readonly string $characterName,
        private readonly bool   $enabled,

    )
    {

    }

    public static function create(
        string $characterName = '',
        bool   $enabled = false,
    ): static
    {
        return new self(
            characterName: $characterName,
            enabled: $enabled,
        );
    }

    public function getCharacterName(): string
    {
        return $this->characterName;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
