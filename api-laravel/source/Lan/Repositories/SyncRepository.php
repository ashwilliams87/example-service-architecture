<?php

namespace Lan\Repositories;

use Ebs\Model\Book as Ebs_Book;
use Ebs\Model\Favorite;
use Ebs\Model\Journal as Ebs_Journal;
use Ebs\Model\Journal_Article as Ebs_Journal_Article;
use Ebs\Model\Journal_Issue as Ebs_Journal_Issue;
use Ebs\Model\Publisher as Ebs_Publisher;
use Ebs\Model\Subscriber;
use Ebs\Model\User_Data;
use Ice\Helper\Date;
use Ice\Model\User;
use Illuminate\Support\Facades\DB;
use Lan\Contracts\Repositories\SyncRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\DTOs\Sync\Requests\DocumentsAndBookmarksAtTimeRequestDTO;
use stdClass;

class SyncRepository implements SyncRepositoryInterface
{
    private int $timeDiff;
    private int $revertTimeToDevice;
    private array $serverData;

    private User $user;

    public function __construct(
        private SecurityServiceInterface $securityService,
    )
    {
        $this->user = $this->securityService->getUser();
        $this->serverData = $this->getServerData();
    }

    public function setTimeDiff(
        int $serverTimeStamp,
        int $deviceTimeStamp,
    ): void
    {
        $this->timeDiff = $serverTimeStamp - $deviceTimeStamp;

        if ($this->timeDiff > 0) {
            $this->revertTimeToDevice = -($this->timeDiff);
        } else {
            $this->revertTimeToDevice = $this->timeDiff;
        }
    }

    private function getServerData(): array
    {
        $bookmarkQuery = '
          SELECT
            Bookmark.id,
            Bookmark.entity,
            Bookmark.entity_id,
            Bookmark.page,
            Bookmark.bookmark_comment,
            Bookmark.bookmark_color,
            Bookmark.bookmark_updated_at,
            Bookmark.type,
            Bookmark.article,
            Bookmark.bookmark_active
          FROM ebs.ebs_bookmark as Bookmark
          WHERE Bookmark.user_id =:user_id;
          ';

        $bookQuery = '
          SELECT
            Favorite.entity,
            Favorite.entity_id,
            Favorite.updated_at,
            Favorite.favorite_active,
            Favorite.favorite_desc
          FROM ebs.ebs_favorite AS Favorite
          WHERE Favorite.user_id =:user_id AND Favorite.entity=\'book\';
        ';

        $articlesQuery = '
          SELECT
            Favorite.entity,
            Favorite.entity_id,
            Favorite.updated_at,
            Favorite.favorite_active,
            Favorite.favorite_desc
          FROM ebs.ebs_favorite AS Favorite
          WHERE Favorite.user_id =:user_id AND Favorite.entity=\'journalArticle\'
        ';

        $localData = [];
        $localData['bookMarks'] = DB::connection('ebs')->select($bookmarkQuery, ['user_id' => $this->user->getPkValue()]);
        $localData['books'] = DB::connection('ebs')->select($bookQuery, ['user_id' => $this->user->getPkValue()]);
        $localData['journalArticles'] = DB::connection('ebs')->select($articlesQuery, ['user_id' => $this->user->getPkValue()]);

        // проебразование результатов из StdClass в array
        foreach ($localData as &$value) {
            $value = $this->convertQueryResultsToArray($value);
        }

        return $localData;
    }

    /**
     * Преобразование результатов запроса, полученных через ларавелевский фасад DB
     * Фасад отдает список объектов StdClass. Данный метод преобразует их в array
     *
     * @param stdClass[] $data
     * @return array[]
     */
    private function convertQueryResultsToArray(array $data): array
    {
        return array_map(function ($object) {
            return get_object_vars($object);
        }, $data);
    }

    public function synchronizeBookMarks(DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void
    {
        $deviceData = $requestDTO->getBookmarks();

        $bookMarks = [];
        foreach ($this->serverData['bookMarks'] as $localKey => $serverBookmark) {
            $serverBookMarkMsk = strtotime($serverBookmark['bookmark_updated_at'] . " UTC");
            foreach ($deviceData as $deviceKey => $deviceBookMark) {
                if ($deviceBookMark['entity'] == 'article') {
                    $deviceBookMark['entity'] = 'journalArticle';
                }

                if (
                    $deviceBookMark['entity'] == $serverBookmark['entity'] &&
                    $deviceBookMark['entity_id'] == $serverBookmark['entity_id'] &&
                    $deviceBookMark['type'] == $serverBookmark['type'] &&
                    $deviceBookMark['page'] == $serverBookmark['page'] &&
                    $deviceBookMark['article'] == $serverBookmark['article']
                ) {
                    $deviceBookMarkMsk = ((int)$deviceBookMark['updated_at']) + $this->timeDiff;
                    if (($deviceBookMarkMsk > $serverBookMarkMsk) && ($deviceBookMark['sync_active'] != $serverBookmark['bookmark_active'])) {
                        $this->serverData['bookMarks'][$localKey]['bookmark_updated_at'] = gmdate('Y-m-d H:i:s', $deviceBookMarkMsk);
                        //file_put_contents('../testLocalBookMarkMsk.txt', PHP_EOL . ' Локальное:' . $localBookMarkMsk . ' устройство:' . ' ' . $deviceBookMarkMsk . ' в базу ' . date('Y-m-d H:i:s', $deviceBookMarkMsk), FILE_APPEND);
                        $this->serverData['bookMarks'][$localKey]['bookmark_active'] = $deviceBookMark['sync_active'];
                        $this->serverData['bookMarks'][$localKey]['user_id'] = $this->user->getPkValue();
                        if ($deviceBookMark['sync_active'] == 0) {
                            $this->serverData['bookMarks'][$localKey]['bookmark_comment'] = '';
                        }
                        $bookMarks[] = $this->serverData['bookMarks'][$localKey];
                    }
                    unset($deviceData[$deviceKey]);
                }
            }
        }
        unset($serverBookmark);
        unset($deviceBookMark);

        // $currentTime = time() + $this->timeDiff;
        foreach ($deviceData as $deviceBookMark) {
            if ($deviceBookMark['sync_active'] == 1) {
                $bookMarks[] = [
                    'entity' => $deviceBookMark['entity'],
                    'entity_id' => $deviceBookMark['entity_id'],
                    'page' => $deviceBookMark['page'],
                    'bookmark_comment' => '',
                    'bookmark_color' => '1',
                    'user_id' => $this->user->getPkValue(),
                    'type' => $deviceBookMark['type'],
                    'article' => $deviceBookMark['article'],
                    'bookmark_active' => $deviceBookMark['sync_active']
                ];
            }
        }

        unset($deviceBookMark);
        if (!empty($bookMarks)) {
            $this->insertMultipleFieldsOnDuplicate(
                tableName: 'ebs.ebs_bookmark',
                columnNames: ['entity', 'entity_id', 'page', 'bookmark_comment', 'bookmark_updated_at', 'bookmark_color', 'user_id', 'type', 'article', 'bookmark_active'],
                dataValues: $bookMarks,
            );
        }
    }

    public function synchronizeFavoriteBooks(DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void
    {
        $this->synchronizeFavorite(
            deviceData: $requestDTO->getBooks(),
            index: 'books',
            entity: 'book',
        );
    }

    public function synchronizeFavoriteJournalArticles(DocumentsAndBookmarksAtTimeRequestDTO $requestDTO): void
    {
        $this->synchronizeFavorite(
            deviceData: $requestDTO->getBooks(),
            index: 'journalArticles',
            entity: 'journalArticle',
        );
    }

    private function synchronizeFavorite(
        array  $deviceData,
        string $index,
        string $entity,
    ): void
    {
        $books = [];

        foreach ($this->serverData[$index] as $localKey => $serverBook) {
            $serverUpdatedAtMsk = strtotime($serverBook['updated_at'] . " UTC");
            foreach ($deviceData as $deviceKey => $deviceBook) {
                if (
                    $entity == $serverBook['entity'] &&
                    $deviceBook['entity_id'] == $serverBook['entity_id']
                ) {
                    $deviceUpdatedAtMsk = ((int)$deviceBook['updated_at']) + $this->timeDiff;
                    if (($deviceUpdatedAtMsk > $serverUpdatedAtMsk) && ($deviceBook['sync_active'] != $serverBook['favorite_active'])) {
                        //$this->serverData[$index][$localKey]['updated_at'] = gmdate('Y-m-d H:i:s', $deviceUpdatedAtMsk);
                        $this->serverData[$index][$localKey]['favorite_active'] = $deviceBook['sync_active'];
                        $this->serverData[$index][$localKey]['user_id'] = $this->user->getPkValue();
                        $books[] = $this->serverData[$index][$localKey];
                        if ($deviceBook['sync_active'] == 0) {
                            $this->serverData[$index][$localKey]['favorite_desc'] = '';
                        }
                    }
                    unset($deviceData[$deviceKey]);
                }
            }
        }
        unset($serverBook);
        unset($deviceBook);

        // $currentTime = $this->serverTime + $this->timeDiff;
        foreach ($deviceData as $deviceBook) {
            if ($deviceBook['sync_active'] == 1) {
                $books[] = [
                    'entity' => $entity,
                    'entity_id' => $deviceBook['entity_id'],
                    'user_id' => $this->user->getPkValue(),
                    'favorite_desc' => '',
                    //'updated_at' => $currentTime,
                    //'updated_at' => gmdate('Y-m-d H:i:s', ((int)$deviceBook['updated_at']) + $this->timeDiff),
                    'favorite_active' => $deviceBook['sync_active']
                ];
            }
        }
        unset($deviceBook);
        if (!empty($books)) {
            $this->insertMultipleFieldsOnDuplicate(
                tableName: 'ebs.ebs_favorite',
                columnNames: ['entity', 'entity_id', 'user_id', 'favorite_desc', 'updated_at', 'favorite_active'],
                dataValues: $books
            );
        }
    }

    public function updateUserLastFavorite(): void
    {
        $user = $this->securityService->getUser();
        User_Data::create([
            '/pk' => $user->getPkValue(),
            'last_favorite' => Date::get()
        ])->save(true);
    }

    public function getServerBookMarks(): array
    {
        //(unix_timestamp(Bookmark.bookmark_updated_at)+:time_diff) as updated_at,
        //Bookmark.bookmark_updated_at as updated_at,
        //(unix_timestamp()+3600*3)+:revert)time, 'revert_time' => $this->revertTimeToDevice
        $query = '
          SELECT
            Bookmark.entity,
            Bookmark.entity_id,
            Bookmark.page,
            Bookmark.bookmark_updated_at as updated_at,
            Bookmark.type,
            Bookmark.article,
            Bookmark.bookmark_active as sync_active
          FROM ebs.ebs_bookmark as Bookmark
          WHERE Bookmark.user_id =:user_id AND Bookmark.bookmark_active =1 AND (Bookmark.entity =\'book\' OR Bookmark.entity=\'journalArticle\')
          ORDER BY Bookmark.page asc;
          ';

        $bookMarks = DB::connection('ebs')->select($query, ['user_id' => $this->user->getPkValue()]);
        $bookMarks = $this->convertQueryResultsToArray($bookMarks);

        if (!$bookMarks) {
            return [];
        }
        foreach ($bookMarks as $key => $bookMark) {
            $bookMarks[$key]['updated_at'] = (strtotime($bookMark['updated_at']) + 3600 * 3) + $this->revertTimeToDevice;
        }

        return $bookMarks;
    }

    public function getServerFavoriteData(): array
    {
        /** @var Subscriber $subscriber */
        $subscriber = $this->securityService->getSubscriber();

        $bookIn = "SELECT Favorite.entity_id FROM `ebs_favorite` AS Favorite WHERE Favorite.user_id =" . (int)$this->user->getPkValue() . " AND Favorite.favorite_active = 1 AND Favorite.entity = 'book'";

        $bookScopeOptions = [
            'subscriber' => $subscriber,
            'mode' => 'default',
            'packets' => ['denormal_dynamic', 'denormal_private', 'static', 'gift', 'extra', 'role', 'seb'],
            'user' => $this->user,
            'userId' => $this->user->getPkValue(),
            'platform' => ['mobile'],
            'filters' => [
                'Book' => ['Book.id IN (' . $bookIn . ')']
            ],
            'fieldNames' => [
                'Available' => ['/pk'],
                'Access' => [
                    'expired_date'
                ]
            ]
        ];

        $availableBooks = Ebs_Book::createQueryBuilder()
            ->scope('newAvailable', $bookScopeOptions)
            ->group()
            ->getSelectQuery(['/pk' => 'id'], [], 'Ice\DataSource\Mysqli/front.ebs');

        $availableFavoriteBookQuery = Ebs_Book::createQueryBuilder()
            ->setDistinct()
            ->func(['MAX' => 'access_expired_date'], 'Available_Book.expired_date')
            ->group('book_pk', [Ebs_Book::class, 'Available_Book'])
            ->getSelectQuery(['/pk', 'expired_date'], [$availableBooks, 'Available_Book'], 'Ice\DataSource\Mysqli/front.ebs');

        $favoriteBookQuery = Favorite::createQueryBuilder()
            ->func(['' => 'access_date'], 'access_expired_date')
            ->func(['' => 'active'], 'CASE WHEN access_expired_date is NULL THEN 0 ELSE 1 END', '')
            ->func(['' => 'publisher'], '`Publisher`.publisher_name')
            ->func(['' => 'issue'], 'NULL')
            ->func(['' => 'journal'], 'NULL')
            ->left([$availableFavoriteBookQuery, 'Available_Book'], null, 'Available_Book.available_book_pk=Favorite.entity_id')
            ->inner(Ebs_Book::class, ['/name' => 'title', 'authors' => 'author'], 'Favorite.entity_id=Book.id AND Book.book_active = 1 AND (Book.book_platform_id is NULL OR Book.book_platform_id=2)')
            ->inner(Ebs_Publisher::class, null, 'Book.publisher_id=Publisher.id')
            ->where('Favorite.favorite_active=1 AND Favorite.user_id=' . $this->user->getPkValue() . ' AND Favorite.entity=\'book\'')
            ->getSelectQuery(['/pk' => 'id', 'user__fk' => 'user_id', '/desc', 'created_at', '/active', 'model_key' => 'entity_id', 'model_class' => 'entity', 'updated_at' => 'updated_at', 'favorite_active' => 'sync_active'], [], 'Ice\DataSource\Mysqli/front.ebs');

        $journalArticleIn = "SELECT Favorite.entity_id FROM `ebs_favorite` AS Favorite WHERE Favorite.user_id =" . $this->user->getPkValue() . " AND Favorite.favorite_active = 1 AND Favorite.entity = 'journalArticle'";

        $journalArticleScopeOptions = [
            'subscriber' => $subscriber,
            'packets' => ['static'],
            'platform' => ['mobile'],
            'filters' => [
                'Journal_Article' => ['Journal_Article.id IN (' . $journalArticleIn . ')']
            ],
            'fieldNames' => [
                'Journal_Article' => ['/pk'],
            ]
        ];

        $availableFavoriteJournalArticleQuery = Ebs_Journal::createQueryBuilder()
            ->scope('newAvailable', $journalArticleScopeOptions)
            ->func(['MAX' => 'access_expired_date'], 'Access.expired_date')
            ->group('journal_article_pk', [Ebs_Journal_Article::class])
            ->getSelectQuery(null, [], 'Ice\DataSource\Mysqli/front.ebs');

        $favoriteJournalArticle = Favorite::createQueryBuilder()
            ->func(['' => 'access_date'], 'access_expired_date')
            ->func(['' => 'active'], 'CASE WHEN access_expired_date is NULL THEN 0 ELSE 1 END', '')
            ->func(['' => 'publisher'], 'NULL')
            ->func(['' => 'issue'], '`Journal_Issue`.journal_issue_name')
            ->func(['' => 'journal'], '`Journal`.journal_name')
            ->left([$availableFavoriteJournalArticleQuery, 'Available_Journal_Article'], null, 'Available_Journal_Article.journal_article_pk=Favorite.entity_id')
            ->inner(Ebs_Journal_Article::class, ['/name' => 'title', 'authors' => 'author'], 'Favorite.entity_id=Journal_Article.id')
            ->inner(Ebs_Journal_Issue::class, null, 'Journal_Issue.id=Journal_Article.journal_issue_id')
            ->inner(Ebs_Journal::class, null, 'Journal.id=Journal_Issue.journal_id AND Journal.journal_active=1 AND (Journal.journal_platform_id is NULL OR Journal.journal_platform_id=2)')
            ->where('Favorite.favorite_active=1 AND Favorite.user_id=' . $this->user->getPkValue() . ' AND Favorite.entity=\'journalArticle\'')
            ->getSelectQuery(['/pk' => 'id', 'user__fk' => 'user_id', '/desc', 'created_at', '/active', 'model_key' => 'entity_id', 'model_class' => 'entity', 'updated_at' => 'updated_at', 'favorite_active' => 'sync_active'], [], 'Ice\DataSource\Mysqli/front.ebs');

        $union = Favorite::createQueryBuilder()
            ->getSelectQuery(['access_date', 'active', 'issue', 'journal', 'title', 'author', 'publisher', 'entity_id', 'entity', 'updated_at', 'sync_active'], [$favoriteBookQuery, $favoriteJournalArticle], 'Ice\DataSource\Mysqli/front.ebs');

        $favorite = Favorite::createQueryBuilder()
            ->desc('updated_at', [Favorite::class, 'Favorite_Union'])
            ->getSelectQuery(['access_date', 'active', 'issue', 'journal', 'title', 'author', 'publisher', 'entity_id' => 'id', 'entity', 'updated_at', 'sync_active'], [$union, 'Favorite_Union'], 'Ice\DataSource\Mysqli/front.ebs')
            ->getRows();

        if (!$favorite) {
            return [];
        }

        foreach ($favorite as $key => $favoriteItem) {
            $favorite[$key]['id'] = (int)$favoriteItem['id'];
            $favorite[$key]['updated_at'] = (strtotime($favoriteItem['updated_at']) + 3600 * 3) + $this->revertTimeToDevice;
            $favorite[$key]['sync_active'] = (int)$favoriteItem['sync_active'];
            $favorite[$key]['active'] = $favoriteItem['active'] == '0' || (!$this->user->isActive()) ? false : true;

            $favorite[$key]['access_date'] = $this->user->get('/expired_at') < Date::get($favoriteItem['access_date'], 'Y-m-d H:i:s')
                ? Date::get($this->user->get('/expired_at'), 'Y-m-d')
                : $favoriteItem['access_date'];

            if ($favoriteItem['entity'] === 'book') {
                $favorite[$key]['cover'] = EBS_DOMAIN . '/img/cover/book/' . $favoriteItem['id'] . '.jpg';
            } else {
                $favorite[$key]['cover'] = null;
                // $favorite[$key]['cover'] = EBS_DOMAIN . '/img/cover/journal/issue/' . $issueForCoverId['id'] . '.jpg';
            }
        }

        return $favorite;
    }

    private function insertMultipleFieldsOnDuplicate(
        string $tableName,
        array  $columnNames,
        array  $dataValues,
        array  $excludedUpdate = []
    ): bool
    {
        $dataToInsert = array();
        foreach ($dataValues as $data) {
            foreach ($columnNames as $colName) {
                // В phalcon-api обращение к $data[$colName] с несуществующим в нем ключом не вызывает исключение
                // phalcon-api записывал в $dataToInsert[] null в таких ситуациях
                // В текущей версии воспроизвел то же поведение через "?? null"
                $dataToInsert[] = $data[$colName] ?? null;
            }
        }

        // (optional) setup the ON DUPLICATE column names
        $updateCols = array();
        $excludedUpdColNames = array_diff($columnNames, $excludedUpdate);
        foreach ($excludedUpdColNames as $curCol) {
            $updateCols[] = $curCol . " = VALUES($curCol)";
        }

        $onDup = implode(', ', $updateCols);

        // setup the placeholders - a fancy way to make the long "(?, ?, ?)..." string
        $rowPlaces = '(' . implode(', ', array_fill(0, count($columnNames), '?')) . ')';
        $allPlaces = implode(', ', array_fill(0, count($dataValues), $rowPlaces));

        $sql = "INSERT INTO $tableName (" . implode(', ', $columnNames) .
            ") VALUES " . $allPlaces . " ON DUPLICATE KEY UPDATE $onDup";
//        file_put_contents('../testSQL.txt', json_encode($sql));
//        file_put_contents(
//            '../testBOOKSINSERT.txt',
//            print_r($dataToInsert,
//                true
//            ),
//            FILE_APPEND
//        );

        return DB::connection('ebs')->statement($sql, $dataToInsert);
    }
}
