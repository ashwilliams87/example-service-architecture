<?php

namespace Lan\Repositories;

use Ebs\Model\Book as Ebs_Book;
use Ebs\Model\Category as Ebs_Category;
use Ebs\Model\Journal as Ebs_Journal;
use Ebs\Model\Journal_Category_Link;
use Ebs\Model\Publisher;
use Ice\Core\DataSource;
use Ice\Core\Query;
use Ice\Core\QueryBuilder;
use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\AuthorRepositoryInterface;
use Lan\Contracts\Repositories\PublisherRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\Enums\DocumentType;

class PublisherRepository implements PublisherRepositoryInterface
{
    public function __construct(
        private SecurityServiceInterface  $securityService,
        private AuthorRepositoryInterface $authorRepository,
    )
    {

    }

    public function getPublisherList(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        if (!$requestDTO->getCategoryId()) {
            return $this->makeEmptyQueryResult();
        }

        $andSubCategory = '';
        if (!empty($requestDTO->getSubCategoryId())) {
            $andSubCategory = ' AND Category.id=' . $requestDTO->getSubCategoryId();
        }

        switch (DocumentType::tryFrom($requestDTO->getType())) {
            case DocumentType::JOURNAL:
                return Publisher::createQueryBuilder()
                    ->inner(Ebs_Journal::class, null, 'Journal.publisher_id=Publisher.id AND Journal.journal_active=1 AND (Journal.journal_platform_id=2 OR Journal.journal_platform_id is NULL)')
                    ->inner(Journal_Category_Link::class, null, 'Journal_Category_Link.journal_id=Journal.id')
                    ->inner(Ebs_Category::class, null, 'Journal_Category_Link.category_id=Category.id AND Category.category_active=1' . $andSubCategory)
                    ->inner([Ebs_Category::class, 'Area_Knowledge'], null, 'Area_Knowledge.id=Category.area_knowledge_id AND Area_Knowledge.category_active=1')
                    ->group()
                    ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
                    ->asc('/name')
                    ->where('Publisher.publisher_active=1 AND `Area_Knowledge`.`id`=' . $requestDTO->getCategoryId())
                    ->getSelectQuery(['/pk' => 'id', '/name' => 'title'], [], 'Ice\DataSource\Mysqli/front.ebs')
                    ->getQueryResult();
            case DocumentType::BOOK:
                $user = $this->securityService->getUser();
                $scopeOptions = [
                    'user' => $user,
                    'userId' => $user->getPkValue(),
                    'subscriber' => $user->getSubscriber(),
                    'packets' => ['denormal_dynamic', 'denormal_private', 'static', 'gift', 'extra', 'role', 'seb'],
                    'mode' => 'default',
                    'platform' => ['mobile'],
                    'filters' => [
                        'Area_Knowledge' => ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getCategoryId()],
                    ],
                    'fieldNames' => [
                        'Publisher' => ['/pk' => 'id', '/name' => 'title'],
                    ]
                ];

                if (!empty($requestDTO->getSubCategoryId())) {
                    $scopeOptions['filters']['Category'] = ['Category.id=' . $requestDTO->getSubCategoryId()];
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
                    ->setCalcFoundRows()
                    ->scope('newAll', $scopeOptions)
                    ->group('publisher_pk', Publisher::class)
                    ->desc('available')
                    ->asc('publisher_name', Publisher::class)
                    ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
                    ->getSelectQuery(null, null, 'Ice\DataSource\Mysqli/front.ebs')
                    ->getQueryResult();
        }

        return $this->makeEmptyQueryResult();
    }

    public function getPrivatePublisherList(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        if ($requestDTO->getType() === DocumentType::JOURNAL->value) {
            return $this->makeEmptyQueryResult();
        } else {
            //$limit = (int)$limit;
            //$offset = (int)$offset;

            $user = $this->securityService->getUser();

            $scopeOptions = [
                'user' => $user,
                'userId' => $user->getPkValue(),
                'subscriber' => $user->getSubscriber(),
                'packets' => ['private'],
                'mode' => 'default',
                'platform' => ['mobile'],
                'filters' => [],
                'fieldNames' => [
                    'Publisher' => ['/pk' => 'id', '/name' => 'title'],
                ]
            ];

            if (!empty($requestDTO->getSubCategoryId())) {
                $scopeOptions['filters']['Area_Knowledge'] = ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getSubCategoryId()];
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
                ->setCalcFoundRows()
                ->scope('newAvailable', $scopeOptions)
                ->group('publisher_pk', Publisher::class)
                ->asc('publisher_name', Publisher::class)
                ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
                ->getSelectQuery(null, null, 'Ice\DataSource\Mysqli/front.ebs')
                ->getQueryResult();
        }
    }

    private function makeEmptyQueryResult(): QueryResult
    {
        $queryBuilder = QueryBuilder::create(Publisher::class);
        $query = Query::create($queryBuilder, null);
        return QueryResult::create($query, []);
    }
}
