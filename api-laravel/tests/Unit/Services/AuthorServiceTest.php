<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Lan\DTOs\Author\Responses\AuthorFirstCharDTO;
use Lan\DTOs\Author\Responses\AuthorFirstCharListDTO;
use Lan\DTOs\Author\Responses\AuthorsMapCharResponseDTO;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;
use Lan\Repositories\AuthorRepository;
use Lan\Services\AuthorService;
use Tests\Support\UnitTester;

class AuthorServiceTest extends Unit
{
    protected array $characterMap = [
        'rus' => [
            'А' => false, 'Б' => false, 'В' => false, 'Г' => false, 'Д' => false,
            'Е' => false, 'Ж' => false, 'З' => false, 'И' => false, 'К' => false,
            'Л' => false, 'М' => false, 'Н' => false, 'О' => false, 'П' => false,
            'Р' => false, 'С' => false, 'Т' => false, 'У' => false, 'Ф' => false,
            'Х' => false, 'Ц' => false, 'Ч' => false, 'Ш' => false, 'Щ' => false,
            'Э' => false, 'Ю' => false, 'Я' => false
        ],
        'eng' => [
            'A' => false, 'B' => false, 'C' => false, 'D' => false, 'E' => false,
            'F' => false, 'G' => false, 'H' => false, 'I' => false, 'J' => false,
            'K' => false, 'L' => false, 'M' => false, 'N' => false, 'O' => false,
            'P' => false, 'Q' => false, 'R' => false, 'S' => false, 'T' => false,
            'U' => false, 'V' => false, 'W' => false, 'X' => false, 'Y' => false,
            'Z' => false
        ],
        'num' => [
            '0-9' => false
        ]
    ];
    protected array $enabledCharacterList = [
        "",
        "",
        "B",
        "G",
        "K",
        "M",
        "P",
        "R",
        "S",
        "T",
        "А",
        "Б",
        "В",
        "Г",
        "Д",
        "Е",
        "Ж",
        "З",
        "И",
        "К",
        "Л",
        "М",
        "Н",
        "О",
        "П",
        "Р",
        "С",
        "Т",
        "У",
        "Ф",
        "Х",
        "Ц",
        "Ч",
        "Ш",
        "Щ",
        "Э",
        "Ю",
        "Я"
    ];

    protected array $characterTypeList = [
        'rus',
        'eng',
        'num',
    ];

    protected UnitTester $tester;

    public function testGetAuthorNonPrivateCharacterMap(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            categoryId: 917,
            subCategoryId: 0,
            publisherId: 0,
            limit: 100,
            offset: 0,
            syntex: 0,
        );

        $authorRepositoryMock = $this->make(AuthorRepository::class, [
            'getAllCharacters' => Expected::never(),
            'getCharacterMap' => Expected::once(function () {
                return $this->characterMap;
            }),
            'getAuthorCharacterList' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) {
                return $this->enabledCharacterList;
            }),
            'getAuthorPrivateCharacterList' => Expected::never(),
        ]);

        $authorService = new AuthorService(
            authorRepository: $authorRepositoryMock,
        );

        $resultDTO = $authorService->getAuthorCharacterMap($requestDTO);

        $this->assertInstanceOf(AuthorsMapCharResponseDTO::class, $resultDTO);
        $this->assertEquals($this->makeCharacterMapResponseDTO(), $resultDTO);
    }

    public function testGetAuthorPrivateCharacterMap(): void
    {
        $requestDTO = new DocumentsTypeRequestDTO(
            categoryId: 0,
            subCategoryId: 0,
            publisherId: 0,
            limit: 100,
            offset: 0,
            syntex: 0,
        );

        $authorRepositoryMock = $this->make(AuthorRepository::class, [
            'getAllCharacters' => Expected::never(),
            'getCharacterMap' => Expected::once(function () {
                return $this->characterMap;
            }),
            'getAuthorCharacterList' => Expected::never(),
            'getAuthorPrivateCharacterList' => Expected::once(function (DocumentsTypeRequestDTO $requestDTO) {
                return $this->enabledCharacterList;
            }),
        ]);

        $authorService = new AuthorService(
            authorRepository: $authorRepositoryMock,
        );

        $resultDTO = $authorService->getAuthorCharacterMap($requestDTO);

        $this->assertInstanceOf(AuthorsMapCharResponseDTO::class, $resultDTO);
        $this->assertEquals($this->makeCharacterMapResponseDTO(), $resultDTO);
    }

    protected function makeCharacterMapResponseDTO(): AuthorsMapCharResponseDTO
    {
        $characterMap = $this->characterMap;
        foreach ($this->enabledCharacterList as $character) {
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
