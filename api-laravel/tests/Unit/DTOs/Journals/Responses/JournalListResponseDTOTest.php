<?php

namespace Tests\Unit\DTOs\Journals\Responses;

use Codeception\Test\Unit;
use Lan\DTOs\Journal\Responses\JournalCardDTO;

class JournalListResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $id = 1;
        $title = 'title';
        $available = 1;
        $allCount = 10;
        $publisher = 'publisher';
        $coverUrl = 'https://ebs.landev.ru/img/cover/issue/1.jpg';

        $dto = JournalCardDTO::createFromIceQueryResultRow([
            'id' => $id,
            'title' => $title,
            'available' => $available,
            'active' => $allCount,
            'all_count' => $allCount,
            'publisher' => $publisher,
            'cover' => $coverUrl,
        ]);

        $this->assertInstanceOf(JournalCardDTO::class, $dto);
        $this->assertEquals($id, $dto->getId());
        $this->assertEquals($title, $dto->getTitle());
        $this->assertEquals($available, $dto->isAvailable());
        $this->assertEquals($allCount, $dto->getAllCount());
        $this->assertEquals($publisher, $dto->getPublisher());
        $this->assertEquals($coverUrl, $dto->getCoverUrl());
    }
}
