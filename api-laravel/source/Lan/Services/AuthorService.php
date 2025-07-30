<?php

namespace Lan\Services;

use Lan\Contracts\DTOs\Author\AuthorCharacterMapResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\AuthorRepositoryInterface;
use Lan\Contracts\Services\AuthorServiceInterface;
use Lan\DTOs\Author\Responses\AuthorFirstCharDTO;
use Lan\DTOs\Author\Responses\AuthorFirstCharListDTO;
use Lan\DTOs\Author\Responses\AuthorsMapCharResponseDTO;

class AuthorService implements AuthorServiceInterface
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
    )
    {

    }

    public function getAuthorCharacterMap(DocumentsTypeRequestDTOInterface $requestDTO): AuthorCharacterMapResponseDTOInterface
    {
        if ($requestDTO->getCategoryId() === 0) {
            $enabledCharacterList = $this->authorRepository->getAuthorPrivateCharacterList($requestDTO);
        } else {
            $enabledCharacterList = $this->authorRepository->getAuthorCharacterList($requestDTO);
        }

        $characterMap = $this->authorRepository->getCharacterMap();

        foreach ($enabledCharacterList as $character) {
            if (!$character) {
                continue;
            }
            if (array_key_exists($character, $characterMap['rus'])) {
                $characterMap['rus'][$character] = true;
                continue;
            }
            if (array_key_exists($character, $characterMap['eng'])) {
                $characterMap['eng'][$character] = true;
                continue;
            }

            $characterMap['num']['0-9'] = true;
        }

        $russianCharactersDTOs = [];
        $englishCharactersDTOs = [];
        $numericCharactersDTOs = [];

        foreach ($characterMap['rus'] as $character => $enabled) {
            $russianCharactersDTOs[] = AuthorFirstCharDTO::create(
                characterName: $character,
                enabled: $enabled,
            );
        }

        foreach ($characterMap['eng'] as $character => $enabled) {
            $englishCharactersDTOs[] = AuthorFirstCharDTO::create(
                characterName: $character,
                enabled: $enabled,
            );
        }

        foreach ($characterMap['num'] as $character => $enabled) {
            $numericCharactersDTOs[] = AuthorFirstCharDTO::create(
                characterName: $character,
                enabled: $enabled,
            );
        }

        return AuthorsMapCharResponseDTO::create(
            russianCharacters: AuthorFirstCharListDTO::createFromRows($russianCharactersDTOs),
            englishCharacters: AuthorFirstCharListDTO::createFromRows($englishCharactersDTOs),
            numericCharacters: AuthorFirstCharListDTO::createFromRows($numericCharactersDTOs),
        );
    }
}
