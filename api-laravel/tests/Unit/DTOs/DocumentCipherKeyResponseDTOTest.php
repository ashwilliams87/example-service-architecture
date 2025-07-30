<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Document\DocumentCipherKeyResponseDTO;

class DocumentCipherKeyResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $key = ['testKey'];
        $status = SuccessOk200::create();

        $dto = DocumentCipherKeyResponseDTO::create(
            key: $key,
            status: $status,
        );

        $this->assertInstanceOf(DocumentCipherKeyResponseDTO::class, $dto);
        $this->assertEquals($key, $dto->getKey());
        $this->assertEquals($status, $dto->getHttpStatusResult());
    }
}
