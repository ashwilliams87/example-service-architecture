<?php

namespace Tests\Unit\DTOs\Journal\Responses;

use Codeception\Test\Unit;
use Lan\DTOs\Journal\Responses\JournalIssueList\JournalIssuesCardDTO;

class JournalIssuesCardDTOTest extends Unit
{
    public function testCreateDTO(): void
    {
        $repositoryReturnedRows = [
            'id' => 2374,
            'title' => 'Cloud of science',
            'issueperyear' => '4',
            'issn' => '2409-031X',
            'vac' => 0,
            'edition' => 'Главный редактор - Никульчев Е. В., д. т. н., профессор проректор, Московский технологический институт (Россия, Москва)',
            'description' => 'Журнал содержит результаты прикладных и фундаментальных научных исследований в области информационных технологий, моделирования систем, прикладных информационны',
            'email' => null,
            'publisher' => 'Московский технологический институт',
            'city' => 'Москва',
            'country' => 'Россия',
            'year' => 2013,
            'publish_year' => 2014,
            'available' => 1,
            'items' => [
                'name' => '1',
                'journal_issue_pk' => 292672,
            ],
            [
                'name' => '2',
                'journal_issue_pk' => 292674,
            ],
            [
                'name' => '3',
                'journal_issue_pk' => 292671,
            ],
            [
                'name' => '4',
                'journal_issue_pk' => 292673,
            ],
            'active' => true,
            'cover' => 'http://ebs.local/img/cover/issue/298734.jpg',
            'years' => [
                [
                    'name' => '2014',
                    'issues' => [
                        [
                            'id' => '292672',
                            'title' => '1',
                        ],
                        [
                            'id' => '292674',
                            'title' => '2',
                        ],
                        [
                            'id' => '292671',
                            'title' => '3',
                        ],
                        [
                            'id' => '292673',
                            'title' => '4',
                        ]
                    ],
                ],
                [
                    'name' => '2015',
                    'issues' => [
                        [
                            'id' => '293105',
                            'title' => '1',
                        ],
                        [
                            'id' => '294974',
                            'title' => '2',
                        ],
                        [
                            'id' => '297093',
                            'title' => '3',
                        ],
                    ]
                ],
                [
                    'name' => '2016',
                    'issues' => [
                        [
                            'id' => '298734',
                            'title' => '1',
                        ]
                    ],
                ],
                [
                    'name' => '2020',
                    'issues' => [
                        [
                            'id' => '325562',
                            'title' => '1',
                        ],
                        [
                            'id' => '325565',
                            'title' => '2',
                        ],
                        [
                            'id' => '325568',
                            'title' => '3',
                        ]
                    ]
                ]
            ]
        ];

        $expectedMobileScheme = [
            'id' => '2374',
            'title' => 'Cloud of science',
            'issueperyear' => '4',
            'issn' => '2409-031X',
            'vac' => 'нет',
            'edition' => 'Главный редактор - Никульчев Е. В., д. т. н., профессор проректор, Московский технологический институт (Россия, Москва)',
            'description' => 'Журнал содержит результаты прикладных и фундаментальных научных исследований в области информационных технологий, моделирования систем, прикладных информационны',
            'email' => '',
            'publisher' => 'Московский технологический институт',
            'city' => 'Москва',
            'country' => 'Россия',
            'year' => '2013',
            'active' => true,
            'cover' => 'http://ebs.local/img/cover/issue/298734.jpg',
            'years' => [
                [
                    'name' => 2014,
                    'issues' => [
                        [
                            'id' => 292672,
                            'title' => '1'
                        ],
                        [
                            'id' => 292674,
                            'title' => '2'
                        ],
                        [
                            'id' => 292671,
                            'title' => '3'
                        ],
                        [
                            'id' => 292673,
                            'title' => '4'
                        ]
                    ]
                ],
                [
                    'name' => 2015,
                    'issues' => [
                        [
                            'id' => 293105,
                            'title' => '1'
                        ],
                        [
                            'id' => 294974,
                            'title' => '2'
                        ],
                        [
                            'id' => 297093,
                            'title' => '3'
                        ]
                    ]
                ],
                [
                    'name' => 2016,
                    'issues' => [
                        [
                            'id' => 298734,
                            'title' => '1'
                        ]
                    ]
                ],
                [
                    'name' => 2020,
                    'issues' => [
                        [
                            'id' => 325562,
                            'title' => '1'
                        ],
                        [
                            'id' => 325565,
                            'title' => '2'
                        ],
                        [
                            'id' => 325568,
                            'title' => '3'
                        ]
                    ]
                ]
            ]
        ];

        $resultDTO = JournalIssuesCardDTO::createFromArray($repositoryReturnedRows);

        $this->assertInstanceOf(JournalIssuesCardDTO::class, $resultDTO);
        $this->assertEquals($resultDTO->toMobileScheme(), $expectedMobileScheme);
    }
}
