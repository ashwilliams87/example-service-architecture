<?php

namespace Lan\Repositories;

use Ebs\Helper\Model;
use Ebs\Model\Book as Ebs_Book;
use Ebs\Model\Book_Category_Link;
use Ebs\Model\Category as Ebs_Category;
use Ebs\Model\Journal as Ebs_Journal;
use Ebs\Model\Journal_Category_Link;
use Ebs\Model\Publisher as Ebs_Publisher;
use Ice\Core\DataSource;
use Ice\Core\QueryResult;
use Illuminate\Support\Facades\DB;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\AuthorRepositoryInterface;
use Lan\Contracts\Repositories\CategoryRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use stdClass;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private SecurityServiceInterface  $securityService,
        private AuthorRepositoryInterface $authorRepository,
    )
    {

    }

    /**
     * @return stdClass[]
     */
    public function getBookCategories(): array
    {
        $subscriber = $this->securityService->getSubscriber();

        $categories = DB::connection('ebs')->select("
            SELECT cat.id, cat.category_name as title
            FROM ebs_category cat
            WHERE cat.level = 1 AND cat.category_active = 1
        ");

        $scopeOptions['packets'] = ['private'];

        $categoriesPrivateRows = Ebs_Book::createQueryBuilder()
            ->scope('newAvailable', $scopeOptions)
            ->limit(1)
            ->group('/pk', [Ebs_Category::class, 'Area_Knowledge'])
            ->afterSelectCallback(function ($row) {
                return [
                    'id' => (string)$row['area_knowledge_pk'],
                    'title' => $row['area_knowledge_name'],
                    'active' => 1
                ];
            })->getSelectQuery(null)
            ->getRows();

        if (!empty($categoriesPrivateRows)) {
            $title = (empty($subscriber->get('private_name', ''))) ? 'Внутренние ресурсы' : $subscriber->get('private_name');

            // Конвертация в объект
            $object = json_decode(json_encode([
                'id' => 0,
                'title' => $title
            ]));
            array_unshift($categories, $object);
        }

        return $categories;
    }

    public function getJournalCategories(): QueryResult
    {
        $subscriber = $this->securityService->getSubscriber();

        $scopeOptions = [
            'subscriber' => $subscriber,
            'packets' => ['static'],
            'platform' => ['mobile'],
            'fieldNames' => [
                'Area_Knowledge' => [
                    '/pk' => 'id',
                    '/name' => 'title'
                ]
            ],
        ];

        return Ebs_Journal::createQueryBuilder()
            ->scope('newAll', $scopeOptions)
            ->group('/pk', [Ebs_Category::class, 'Area_Knowledge'])
            ->desc('available')
            ->asc('/pk', [Ebs_Category::class, 'Area_Knowledge'])
            ->getSelectQuery(null, [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getQueryResult();
    }

    public function getAllCategories(): array
    {
        return DB::connection('ebs')->select("
            SELECT cat.id, cat.category_name as title
            FROM ebs_category cat
            WHERE cat.level = 1 AND cat.category_active = 1
        ");
    }

    public function getBookSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        $user = $this->securityService->getUser();

        $scopeOptions = [
            'user' => $user,
            'userId' => $user->getPkValue(),
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['denormal_dynamic', 'denormal_private', 'static', 'gift', 'extra', 'role', 'seb'],
            'mode' => 'default',
            'platform' => ['mobile'],
            'filters' => [],
            'fieldNames' => [
                'Category' => [
                    '/pk' => 'id',
                    '/name' => 'title'
                ],
            ]
        ];

        if ($requestDTO->getCategoryId()) {
            $scopeOptions['filters']['Area_Knowledge'] = ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getCategoryId()];
        }

        if ($requestDTO->getPublisherId()) {
            $scopeOptions['filters']['Publisher'] = ['Publisher.id=' . $requestDTO->getPublisherId()];
        }

        $bookFilters = [];
        if (!empty($requestDTO->getAuthor())) {
            $bookFilters[] = "Left(authors, 1)='" . DataSource::getInstance()->escapeString(mb_substr($requestDTO->getAuthor(), 0, 1)) . "'";
        }

        if ('0' === $requestDTO->getAuthor()) {
            $chars = $this->authorRepository->getAllChars();
            foreach ($chars as $k => $v) {
                $chars[$k] = '\'' . $v . '\'';
            }
            $bookFilters[] = "Left(authors, 1) NOT IN(" . implode(',', $chars) . ")";
        }

        if ($requestDTO->getSyntex()) {
            $bookFilters[] = 'Book.synthesizer_path IS NOT NULL';
        }

        if (!empty($bookFilters)) {
            $scopeOptions['filters'] = array_merge($scopeOptions['filters'], ['Book' => $bookFilters]);
        }

        return Ebs_Book::createQueryBuilder()
            ->scope('newAll', $scopeOptions)
            ->group('/pk', [Ebs_Category::class])
            ->getSelectQuery(null, [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getQueryResult();
    }

    public function getJournalSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        $queryBuilder = Ebs_Journal::createQueryBuilder()
            ->inner(Journal_Category_Link::class, null, 'Journal_Category_Link.journal_id=Journal.id')
            //->inner(Ebs_Category::class, ['/pk' => 'sub_category_pk', '/name' => 'sub_category_name', 'parent_categories__json', 'child_categories__json'], 'Journal_Category_Link.category_id=Category.id AND Category.category_active=1')
            ->inner(Ebs_Category::class, ['/pk' => 'id', '/name' => 'title'], 'Journal_Category_Link.category_id=Category.id AND Category.category_active=1')
            ->inner([Ebs_Category::class, 'Area_Knowledge'], null, 'Area_Knowledge.id=Category.area_knowledge_id AND Area_Knowledge.category_active=1');
        if ($requestDTO->getPublisherId()) {
            $queryBuilder->inner(Ebs_Publisher::class, null, 'Journal.publisher_id=Publisher.id AND Publisher.id=' . $requestDTO->getPublisherId());
        }
        return $queryBuilder
            ->where('Journal.journal_active=1 AND Area_Knowledge.id=' . $requestDTO->getCategoryId())
            ->group('/pk', [Ebs_Category::class, 'Category'])
            ->asc('title')
            ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
            ->getSelectQuery(null, [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getQueryResult();
    }

    public function getAllSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        $union = [];
        $union[] = Ebs_Book::createQueryBuilder()
            ->inner(Book_Category_Link::class, null, 'Book_Category_Link.book_id=Book.id')
            ->inner(Ebs_Category::class, ['/pk' => 'id', '/name', 'parent_categories__json', 'child_categories__json'], 'Book_Category_Link.category_id=Category.id AND Category.category_active=1')
            ->inner([Ebs_Category::class, 'Area_Knowledge'], null, 'Area_Knowledge.id=Category.area_knowledge_id AND Area_Knowledge.category_active=1')
            ->where('Book.book_active=1 AND Book.extra=0 AND Book.book_private=0 AND Area_Knowledge.id=' . $requestDTO->getCategoryId() . ' AND (Book.book_platform_id IS NULL OR Book.book_platform_id=2)')
            ->group('/pk', [Ebs_Category::class, 'Category'])
            ->getSelectQuery(null, [], 'Ice\DataSource\Mysqli/front.ebs');


        $union[] = Ebs_Journal::createQueryBuilder()
            ->inner(Journal_Category_Link::class, null, 'Journal_Category_Link.journal_id=Journal.id')
            ->inner(Ebs_Category::class, ['/pk' => 'id', '/name', 'parent_categories__json', 'child_categories__json'], 'Journal_Category_Link.category_id=Category.id AND Category.category_active=1')
            ->inner([Ebs_Category::class, 'Area_Knowledge'], null, 'Area_Knowledge.id=Category.area_knowledge_id AND Area_Knowledge.category_active=1')
            ->where('Journal.journal_active=1 AND Area_Knowledge.id=' . $requestDTO->getCategoryId())
            ->group('/pk', [Ebs_Category::class, 'Category'])
            ->getSelectQuery(null, [], 'Ice\DataSource\Mysqli/front.ebs');

        $avialable = Ebs_Category::createQueryBuilder()
            ->getSelectQuery(['/pk'], $union, 'Ice\DataSource\Mysqli/front.ebs');

        return Ebs_Category::createQueryBuilder()
            ->group('id', [Ebs_Category::class, 'Available'])
            ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
            // ->getSelectQuery(['id' => 'sub_category_pk', 'category_name' => 'sub_category_name', 'parent_categories__json', 'child_categories__json'], [$avialable, 'Available'])
            ->asc('title')
            ->getSelectQuery(['id', 'category_name' => 'title'], [$avialable, 'Available'], 'Ice\DataSource\Mysqli/front.ebs')
            ->getQueryResult();
    }

    public function getPrivateSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        $scopeOptions = [
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['private'],
            'platform' => ['mobile'],
            'filters' => [],
            'fieldNames' => [
                'Area_Knowledge' => [
                    '/pk'
                ]
            ],
        ];

        if ($requestDTO->getPublisherId()) {
            $scopeOptions['filters']['Publisher'] = ['Publisher.id=' . $requestDTO->getPublisherId()];
        }

        $bookFilters = [];
        if (!empty($requestDTO->getAuthor())) {
            $bookFilters[] = "Left(authors, 1)='" . DataSource::getInstance()->escapeString(mb_substr($requestDTO->getAuthor(), 0, 1)) . "'";
        }

        if ('0' === $requestDTO->getAuthor()) {
            $chars = $this->authorRepository->getAllChars();
            foreach ($chars as $k => $v) {
                $chars[$k] = '\'' . $v . '\'';
            }
            $bookFilters[] = "Left(authors, 1) NOT IN(" . implode(',', $chars) . ")";
        }

        if ($requestDTO->getSyntex()) {
            $bookFilters[] = 'Book.synthesizer_path IS NOT NULL';
        }

        if (!empty($bookFilters)) {
            $scopeOptions['filters'] = array_merge($scopeOptions['filters'], ['Book' => $bookFilters]);
        }

        //взяли области dynamic и static
        return Ebs_Category::createQueryBuilder()
            ->group()
            ->func(['' => 'active'], 'CASE WHEN area_knowledge_pk is NULL THEN 0 ELSE 1 END', '')
            ->inner([Model::getAvailableBookQuery($scopeOptions), 'Available'], [], 'Category.area_knowledge_id=Available.area_knowledge_pk')
            ->eq(['/active' => 1])
            ->where('Category.id=Category.area_knowledge_id')
            ->desc('active')
            ->asc('title')
            ->getSelectQuery([
                '/pk' => 'id',
                '/name' => 'title'
            ], [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getQueryResult();
    }
}
