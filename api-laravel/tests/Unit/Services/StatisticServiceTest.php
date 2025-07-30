<?php

namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Lan\Contracts\DTOs\Statistic\StatisticItemsDTOInterface;
use Lan\DTOs\Statistic\Requests\LogReadStatisticRequestDTO;
use Lan\Repositories\StatisticRepository;
use Lan\Services\StatisticService;
use Tests\Support\UnitTester;

class StatisticServiceTest extends Unit
{
    protected UnitTester $tester;

    public function testLogReadStatistic(): void
    {
        $statisticItems = [
            [
                'entity' => 'book',
                'entity_id' => 195479,
                'page' => 20,
            ],
            [
                'entity' => 'journalArticle',
                'entity_id' => 599632,
                'page' => 10,
            ],
        ];

        $requestDTO = new LogReadStatisticRequestDTO(
            statisticItems: $statisticItems
        );

        $statisticRepositoryMock = $this->make(StatisticRepository::class, [
            'logReadStatisticItemsToAuthenticatedUserSubscriber' =>
                Expected::once(function (StatisticItemsDTOInterface $statisticItemsDTO) use ($statisticItems): void{
                    $this->assertInstanceOf(StatisticItemsDTOInterface::class, $statisticItemsDTO);
                    $this->assertEquals($statisticItems, $statisticItemsDTO->getStatisticItems());
                }),
        ]);

        $staticService = new StatisticService($statisticRepositoryMock);

        $staticService->logReadStatistic($requestDTO);
    }
}
