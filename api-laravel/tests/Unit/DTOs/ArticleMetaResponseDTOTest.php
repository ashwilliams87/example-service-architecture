<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Document\DocumentMetaResponseDTO;

class ArticleMetaResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $meta = 'testValue';
        $status = SuccessOk200::create();

        $dto = DocumentMetaResponseDTO::create(
            meta: $meta,
            status: $status,
        );

        $this->assertInstanceOf(DocumentMetaResponseDTO::class, $dto);
        $this->assertEquals($meta, $dto->getMeta());
        $this->assertEquals($status, $dto->getHttpStatusResult());
    }
}
