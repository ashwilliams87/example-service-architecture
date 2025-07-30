<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DataTypes\EbsCarbon;
use Lan\DTOs\Search\Responses\SearchBookCardDTO;

class SearchBooksResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $dto = SearchBookCardDTO::createFromIceQueryResultRow([
            'id' => 45332,
            'name' => 'Планирование на предприятии',
            'word' => 'тесты',
            'snippet' => '',
            'hasPdf' => true,
            'hasEpub' => false,
            'hasAudio' => false,
            'hasSyntex' => false,
            'book_expired' => '2999-12-31',
            'book_private' => false,
            'synthesizer_editor' => '',
            'synthesizer_quality' => 0,
            'available' => false,
            'authors' => 'Кораблев А.И.',
            'publisher_name' => 'лесотехнический университет'
        ]);
        $this->assertEquals(45332, $dto->getId());
        $this->assertEquals('Планирование на предприятии', $dto->getTitle());
        $this->assertEquals('тесты', $dto->getWord());
        $this->assertEquals('', $dto->getSnippet());
        $this->assertTrue($dto->isHasPdf());
        $this->assertFalse($dto->isHasEpub());
        $this->assertFalse($dto->isHasAudio());
        $this->assertFalse($dto->isHasSyntex());
        $this->assertEquals(EbsCarbon::parse('2999-12-31'), $dto->getBookExpired());
        $this->assertFalse($dto->isBookPrivate());
        $this->assertEquals('', $dto->getSynthesizerEditor());
        $this->assertEquals(0, $dto->getSynthesizerQuality());
        $this->assertFalse($dto->isAvailable());
        $this->assertEquals('Кораблев А.И.', $dto->getAuthor());
        $this->assertEquals('лесотехнический университет', $dto->getPublisher());
    }
}
