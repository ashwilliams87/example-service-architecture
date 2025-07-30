<?php

namespace Tests\Unit\DTOs\Journals\Responses;

use Codeception\Test\Unit;
use Lan\DataTypes\EbsCarbon;
use Lan\DTOs\Journal\Responses\ArticleCardDTO;

class ArticleCardDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        {
            $testData = [
                'id' => 1,
                'available' => true,
                'journalId' => 292672,
                'year' => 2014,
                'description' => 'Test description',
                'title' => 'Test Journal Title',
                'author' => 'Author',
                'pages' => '5-10',
                'access_date' => "2025-06-03 12:20:53",
                'all_count' => 1,
                'journal_name' => 'Journal name',
                'journal_issue_pk' => 292672,
                'issue' => 1,
                'publisher' => 'Московский технологический институт',
                'start_page' => 5,
                'finish_page' => 16,
            ];

            $dto = ArticleCardDTO::createFromIceQueryResultRow($testData);

            $this->assertInstanceOf(ArticleCardDTO::class, $dto);
            $this->assertEquals($testData['id'], $dto->getId());
            $this->assertEquals($testData['available'], $dto->isAvailable());
            $this->assertEquals($testData['journalId'], $dto->getJournalId());
            $this->assertEquals($testData['year'], $dto->getIssueYear());
            $this->assertEquals($testData['description'], $dto->getDescription());
            $this->assertEquals($testData['title'], $dto->getTitle());
            $this->assertEquals($testData['author'], $dto->getAuthor());
            $this->assertEquals($testData['pages'], $dto->getPageRange());
            $this->assertEquals(EbsCarbon::parse($testData['access_date']), $dto->getAccessDate());
            $this->assertEquals($testData['all_count'], $dto->getAllCount());
            $this->assertEquals($testData['journal_name'], $dto->getJournalName());
            $this->assertEquals($testData['journal_issue_pk'], $dto->getJournalIssuePk());
            $this->assertEquals($testData['issue'], $dto->getIssueName());
            $this->assertEquals($testData['publisher'], $dto->getPublisher());
            $this->assertEquals($testData['start_page'], $dto->getStartPage());
            $this->assertEquals($testData['finish_page'], $dto->getFinishPage());
        }
    }
}
