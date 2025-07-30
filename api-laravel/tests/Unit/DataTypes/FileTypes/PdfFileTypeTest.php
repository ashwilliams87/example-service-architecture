<?php

namespace Tests\Unit\DataTypes\FileTypes;

use Codeception\Test\Unit;
use Lan\DataTypes\FileTypes\PdfFileType;

class PdfFileTypeTest extends Unit
{
    public function testConstructAndAssertInstance(): void
    {
        $pdfFileType = new PdfFileType();

        $this->assertInstanceOf(PdfFileType::class, $pdfFileType);
        $this->assertInstanceOf(PdfFileType::class, $pdfFileType);
    }

    public function testGetName(): void
    {
        $this->assertEquals('pdf', (new PdfFileType())->getName());
    }
}
