<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DTOs\Category\Responses\CategoryDTO;

class CategoryDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $dto = CategoryDTO::createFromIceQueryResultRow([
            'id' => 45332,
            'title' => 'Категория',
            'available' => 1
        ]);

        $this->assertInstanceOf(CategoryDTO::class, $dto);
        $this->assertEquals(45332, $dto->getId());
        $this->assertEquals('Категория', $dto->getTitle());
        $this->assertTrue($dto->isActive());
    }
}
