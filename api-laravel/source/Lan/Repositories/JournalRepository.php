<?php

namespace Lan\Repositories;

use Ebs\Model\Category as Ebs_Category;
use Ebs\Model\Journal;
use Ebs\Model\Journal_Article;
use Ebs\Model\Journal_Issue;
use Ice\Core\QueryResult;
use Ice\Helper\Date;
use Ice\Model\City;
use Ice\Model\Country;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\JournalRepositoryInterface;
use Lan\Contracts\Repositories\UserRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\Helpers\DocumentHelper;

class JournalRepository implements JournalRepositoryInterface
{
    public function __construct(
        private SecurityServiceInterface $securityService,
        private UserRepositoryInterface $userRepository,
    )
    {

    }

    public function getJournalsFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        $scopeOptions = [
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['static'],
            'platform' => ['mobile'],
            'filters' => [
                'Category' => ['Category.area_knowledge_id=' . $requestDTO->getCategoryId()]
            ],
            'fieldNames' => [
                'Publisher' => ['publisher_name' => 'publisher'],
                'Journal' => ['/pk' => 'id', '/name' => 'title']
            ]
        ];

        if ($requestDTO->getPublisherId()) {
            $scopeOptions['filters']['Publisher'] = 'Publisher.id=' . $requestDTO->getPublisherId();
        }

        if ($requestDTO->getSubCategoryId()) {
            $row = Ebs_Category::createQueryBuilder()
                ->eq(['/pk' => $requestDTO->getSubCategoryId()])
                ->ne('child_categories__json', '[]')
                ->ne('area_knowledge__fk', $requestDTO->getSubCategoryId())
                ->getSelectQuery(['/pk', 'child_categories__json'], [], 'Ice\DataSource\Mysqli/front.ebs')
                ->getRow();
            if ($row) {
                $subCategoryArr = json_decode($row['child_categories__json'], true);
                $subCategoryArr[] = $requestDTO->getSubCategoryId();
                $scopeOptions['filters']['Category'][] = 'Category.id IN(' . implode(',', $subCategoryArr) . ')';
            } else {
                $scopeOptions['filters']['Category'][] = 'Category.id=' . $requestDTO->getSubCategoryId();
            }
        }

        //после применения всех фильтров получаем
        return Journal::createQueryBuilder()
            ->scope('newAll', $scopeOptions)
            ->setCalcFoundRows()
            ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
            ->desc('available')
            ->asc('/name')
            ->asc('publisher_name')
            ->group()
            ->addRowsTransformCallback(function ($journals) {
                $journals = Journal_Issue::addCoverLinks(array_column($journals, 'id'), $journals);
                return $journals;
            })
            ->getSelectQuery(['/pk' => 'id', '/name' => 'title'], [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getQueryResult();
    }

    public function getJournalsWithCoverLinksFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): array
    {
        $queryResult = $this->getJournalsFromCatalog($requestDTO);
        $journalsWithCover = $this->addCoverLinksToJournalRows($queryResult->getRows());
        foreach ($journalsWithCover as $key => $journal) {
            $journalsWithCover[$key]['active'] = $journal['available'] && $this->userRepository->isAuthenticatedUserActive();
        }
        return [
            'journals' => $journalsWithCover,
            'count' => $queryResult->getFoundRows()
        ];
    }

    public function getArticlesFromJournal(DocumentIdRequestDTOInterface $requestDTO): array
    {
        $scopeOptions = [
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['static'],
            'platform' => ['mobile'],
            'filters' => [
                'Journal_Issue' => ['Journal_Issue.id=' . $requestDTO->getId()]
            ],
            'fieldNames' => [
                'Publisher' => [
                    'publisher_name' => 'publisher',
                ],
                'Journal' => [
                    '/pk',
                    '/name',
                ],
                'Journal_Issue' => [
                    '/pk',
                    '/name' => 'issue',
                    'publish_year' => 'year'
                ],
                'Journal_Article' => [
                    '/pk' => 'id',
                    '/name' => 'title',
                    'authors' => 'author',
                    'start_page',
                    'finish_page',
                    '/desc' => 'description'
                ],
                'Access' => ['expired_date']
            ]
        ];

        //после применения всех фильтров получаем
        $articles = Journal::createQueryBuilder()
            ->scope('newAll', $scopeOptions)
            ->func(['MAX' => 'access_date'], 'expired_date')
            ->func(['CONCAT' => 'pages'], 'start_page,\'-\',finish_page')
            ->desc('available')
            ->asc('start_page')
            ->group('/pk', Journal_Article::class)
            ->getSelectQuery([
                '/pk' => 'journalId',
                '/name' => 'journalName',
            ], [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getRows(null, false);

        $expiredDate = Date::get(Date::START, Date::FORMAT_MYSQL_DATE);
        if ($articles) {
            $journalArticle = Journal_Article::create(reset($articles));
            $expiredDate = DocumentHelper::getExpiredDate($journalArticle, $this->securityService->getUser());
        }

        foreach (array_keys($articles) as $key) {
            $articles[$key]['access_date'] = $expiredDate;
        }

        return $articles;
    }

    public function getJournal(int $journalId): Journal
    {
        $journal = Journal::getModel($journalId, '/pk');

        if ($journal) {
            return $journal;
        }

        return Journal::create();
    }

    public function getArticle(int $articleId): Journal_Article
    {
        $options = [
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['static'],
            'platform' => ['mobile'],
            'filters' => [
                'Journal_Article' => ['Journal_Article.id=' . $articleId]
            ],
            'fieldNames' => [
                'Journal_Article' => ['/pk', 'journal_issue__fk', 'pdf_path', 'epub_path', 'synthesizer_path', 'audio_path', 'publish_year'],
                'Journal_Issue' => ['publish_year', 'journal__fk'],
                'Journal' => ['publisher__fk'],
                'Access' => ['expired_date']
            ]
        ];

        $article = Journal::createQueryBuilder()
            ->scope('newAll', $options)
            ->group('/pk', Journal_Article::class)
            ->getSelectQuery(null)
            ->getModel(Journal_Article::class);

        if ($article) {
            return $article;
        }

        return Journal_Article::create();
    }

    public function getJournalWithIssues(int $journalId): array
    {
        $scopeOptions = [
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['static'],
            'platform' => ['mobile'],
            'filters' => [
                'Journal' => ['Journal.id=' . $journalId]
            ],
            'fieldNames' => [
                'Publisher' => [
                    'publisher_name' => 'publisher',
                ],
                'Journal_Issue' => ['/name' => 'name', '/pk', 'publish_year'],
            ],

        ];

        //TODO не работало в последнем коммите
        //после применения всех фильтров получаем
        $resultRows = Journal::createQueryBuilder()
            ->scope('newAll', $scopeOptions)
            ->left(City::class, ['/name' => 'city'], 'Journal.city_id=City.city_pk')
            ->left(Country::class, ['/name' => 'country'], 'Country.country_pk=City.country__fk')
            ->asc('publish_year')
            ->asc('journal_issue_name')
            ->group('/pk', Journal_Article::class)
            ->getSelectQuery(['/pk' => 'id',
                '/name' => 'title',
                'issues_per_year' => 'issueperyear',
                'issn',
                'vac',
                'editors' => 'edition',
                'journal_desc' => 'description',
                'email',
                'foundation_year' => 'year',
                'city__fk'
            ], [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getGroup([
                'id',
                'title',
                'issueperyear',
                'issn',
                'vac',
                'edition',
                'description',
                'email',
                'publisher',
                'city',
                'country',
                'year',
                'publish_year',
                'available'
            ],
                ['items' => ['name', 'journal_issue_pk']]);

        foreach ($resultRows as $key => $row) {
            $resultRows[$key]['active'] = $row['available'] && $this->userRepository->isAuthenticatedUserActive();
        }

        return $resultRows;
    }

    public function getJournalWithIssuesWithCover(int $journalId): array
    {
        $rows = $this->getJournalWithIssues($journalId);
        $journal = reset($rows);
        if(!$journal){
            return [];
        }

        $journal['cover'] = $this->getJournalIssueCoverLink(
            journalId: $journalId,
            journalTitle: $journal['title'],
            journalPublisher: $journal['publisher']
        );

        $journalYears = [];
        foreach ($rows as $item) {
            $journalYears['name'] = (string)$item['publish_year'];
            foreach ($item['items'] as $issueItem) {
                $journalYears['issues'][] = [
                    'id' => (string)$issueItem['journal_issue_pk'],//вот так вот
                    'title' => $issueItem['name']
                ];
            }
            $journal['years'][] = $journalYears;
            unset($journalYears);
        }

        return $journal;
    }

    public function addCoverLinksToJournalRows(array $journalRows): array
    {
        return Journal_Issue::addCoverLinks(
            keys: array_column($journalRows, 'id'),
            journalCollection: $journalRows,
        );
    }

    private function getJournalIssueCoverLink(int $journalId, string $journalTitle, string $journalPublisher): string
    {
        return Journal_Issue::addCoverIssueLink($journalId, $journalTitle, $journalPublisher);
    }
}
