<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DTOs\Author\Responses\AuthorFirstCharDTO;
use Lan\DTOs\Author\Responses\AuthorFirstCharListDTO;
use Lan\DTOs\Author\Responses\AuthorsMapCharResponseDTO;

class AuthorCharacterMapResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $characterTypeList = [
            'rus',
            'eng',
            'num',
        ];
        $paginatedCharacterDTOs = [];
        foreach($characterTypeList as $characterType){
            $paginatedCharacterDTOs[$characterType] = AuthorFirstCharListDTO::createFromRows(
                [
                    AuthorFirstCharDTO::create(
                        characterName: 'A',
                        enabled: true,
                    ),
                    AuthorFirstCharDTO::create(
                        characterName: 'B',
                        enabled: false,
                    ),
                ]
            );
        }

        $resultDTO = AuthorsMapCharResponseDTO::create(
            russianCharacters: $paginatedCharacterDTOs['rus'],
            englishCharacters: $paginatedCharacterDTOs['eng'],
            numericCharacters: $paginatedCharacterDTOs['num'],
        );

        $this->assertInstanceOf(AuthorsMapCharResponseDTO::class, $resultDTO);
        $this->assertEquals($paginatedCharacterDTOs['rus'], $resultDTO->getRussianCharacterListDTO());
        $this->assertEquals($paginatedCharacterDTOs['eng'], $resultDTO->getEnglishCharacterListDTO());
        $this->assertEquals($paginatedCharacterDTOs['num'], $resultDTO->getNumericCharacterListDTO());
    }
}
