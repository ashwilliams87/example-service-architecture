<?php

namespace Tests\Unit\DataTypes\FileTypes;

use Codeception\Test\Unit;
use Lan\DataTypes\FileTypes\TextFileType;

class TextFileTypeTest extends Unit
{
    public function testConstructAndAssertInstance(): void
    {
        $pdfFileType = new TextFileType();

        $this->assertInstanceOf(TextFileType::class, $pdfFileType);
        $this->assertInstanceOf(TextFileType::class, $pdfFileType);
    }

    public function testGetName(): void
    {
        $this->assertEquals('text', (new TextFileType())->getName());
    }
}
