<?php

namespace Tests\Unit\DataTypes\FileTypes;

use Codeception\Test\Unit;
use Lan\DataTypes\FileTypes\EpubFileType;

class EpubTypeTest extends Unit
{
    public function testConstructAndAssertInstance(): void
    {
        $pdfFileType = new EpubFileType();

        $this->assertInstanceOf(EpubFileType::class, $pdfFileType);
        $this->assertInstanceOf(EpubFileType::class, $pdfFileType);
    }

    public function testGetName(): void
    {
        $this->assertEquals('epub', (new EpubFileType())->getName());
    }
}
