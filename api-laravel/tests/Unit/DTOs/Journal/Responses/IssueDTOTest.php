<?php

namespace Tests\Unit\DTOs\Journal\Responses;

use Codeception\Test\Unit;
use Lan\DTOs\Journal\Responses\JournalIssueList\IssueDTO;

class IssueDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $id = 432;
        $title = 'issue-name';
        $dto = IssueDTO::createFromArray([
            'id' => $id,
            'title' => $title,
        ]);

        $this->assertInstanceOf(IssueDTO::class, $dto);
        $this->assertEquals($id, $dto->getId());
        $this->assertEquals($title, $dto->getTitle());
    }
}
