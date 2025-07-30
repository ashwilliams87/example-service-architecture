<?php

namespace Tests\Unit\DataTypes\FileTypes;

use Codeception\Test\Unit;
use Lan\DataTypes\FileTypes\AudioFileType;

class AudioFileTypeTest extends Unit
{
    public function testConstructAndAssertInstance(): void
    {
        $pdfFileType = new AudioFileType();

        $this->assertInstanceOf(AudioFileType::class, $pdfFileType);
        $this->assertInstanceOf(AudioFileType::class, $pdfFileType);
    }

    public function testGetName(): void
    {
        $this->assertEquals('audio', (new AudioFileType())->getName());
    }
}
