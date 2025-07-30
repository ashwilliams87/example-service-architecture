<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DTOs\Search\Responses\SearchJournalCardDTO;

class SearchJournalResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $dto = SearchJournalCardDTO::createFromIceQueryResultRow([
            'id' => 2374,
            'name' => 'Cloud of science',
            'word' => 'science',
            'snippet' => '',
            'available' => false,
            'publisher' => 'Московский технологический институт',
            'cover' => 'https://ebs.landev.ru/img/cover/issue/298734.jpg',
        ]);

        $this->assertEquals(2374, $dto->getId());
        $this->assertEquals('Cloud of science', $dto->getTitle());
        $this->assertEquals('science', $dto->getWord());
        $this->assertEquals('', $dto->getSnippet());
        $this->assertFalse($dto->isAvailable());
        $this->assertEquals('Московский технологический институт', $dto->getPublisher());
        $this->assertEquals('https://ebs.landev.ru/img/cover/issue/298734.jpg', $dto->getCoverUrl());
    }
}
