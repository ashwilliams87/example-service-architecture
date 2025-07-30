<?php

namespace Tests\Unit\DTOs\Journal\Responses;

use Codeception\Test\Unit;
use Lan\DTOs\Journal\Responses\JournalIssueList\YearWithIssuesDTO;

class YearWithIssuesDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $expectedResult = [
            "name" => 2000,
            "issues" => [
                [
                    "title" => "1",
                    "id" => 292672
                ],
                [
                    "title" => "2",
                    "id" => 292674
                ],
                [
                    "title" => "3",
                    "id" => 292671
                ],
                [
                    "title" => "4",
                    "id" => 292673
                ]
            ]
        ];

        $issueList = YearWithIssuesDTO::createFromIceQueryResultRow($expectedResult);

        $this->assertInstanceOf(YearWithIssuesDTO::class, $issueList);
        $this->assertEquals(2000, $issueList->getYear());
        $this->assertEquals($expectedResult, $issueList->toMobileScheme());

    }
}
