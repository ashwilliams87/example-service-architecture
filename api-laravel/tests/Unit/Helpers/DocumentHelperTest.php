<?php

namespace Tests\Unit\Helpers;

use Codeception\Test\Unit;
use Lan\Enums\DocumentType;
use Lan\Helpers\DocumentHelper;
use Tests\Support\UnitTester;

class DocumentHelperTest extends Unit
{
    protected UnitTester $tester;

    public function testCheckIfDocumentTypeIsBook(): void
    {
        $this->assertTrue(DocumentHelper::checkIfDocumentTypeIsBook(DocumentType::BOOK->value));
        $this->assertFalse(DocumentHelper::checkIfDocumentTypeIsBook(DocumentType::JOURNAL->value));
    }

    public function testCheckIfDocumentTypeIsJournal(): void
    {
        $this->assertTrue(DocumentHelper::checkIfDocumentTypeIsJournal(DocumentType::JOURNAL->value));
        $this->assertFalse(DocumentHelper::checkIfDocumentTypeIsJournal(DocumentType::BOOK->value));
    }
}
