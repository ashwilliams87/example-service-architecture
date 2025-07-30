<?php

namespace Tests\Unit\DTOs;

use Codeception\Test\Unit;
use Lan\DTOs\Search\Responses\SearchArticleCardDTO;

class SearchArticlesResponseDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $dto = SearchArticleCardDTO::createFromIceQueryResultRow([
            'id' => 702590,
            'name' =>  'Обобщённая математическая модель динамики...',
            'snippet' =>  'Breki',
            'available' =>  false,
            'start_page' =>  179,
            'finish_page' =>  190,
            'pages' =>  12,
            'authors' =>  'Бреки А. Д.',
            'publisher_name' =>  'Педагогический университет им. Л.Н. Толстого',
            'journal_id' =>  2917,
            'journal_name' =>  'Чебышевский сборник',
            'issue_name' =>  '2',
            'issue_year' =>  2022,
            'journal_article_desc' =>  'В статье представлена обобщённая эмпирическая...'
        ]);

        $this->assertEquals(702590, $dto->getId());
        $this->assertEquals('Обобщённая математическая модель динамики...', $dto->getTitle());
        $this->assertEquals('Breki', $dto->getSnippet());
        $this->assertFalse($dto->isAvailable());
        $this->assertEquals(179, $dto->getStartPage());
        $this->assertEquals(190, $dto->getFinishPage());
        $this->assertEquals(12, $dto->getPageTotalCount());
        $this->assertEquals('Бреки А. Д.', $dto->getAuthor());
        $this->assertEquals('Педагогический университет им. Л.Н. Толстого', $dto->getPublisher());
        $this->assertEquals(2917, $dto->getJournalId());
        $this->assertEquals('Чебышевский сборник', $dto->getJournalName());
        $this->assertEquals('2', $dto->getIssueName());
        $this->assertEquals(2022, $dto->getIssueYear());
        $this->assertEquals('В статье представлена обобщённая эмпирическая...', $dto->getDescription());
    }
}
