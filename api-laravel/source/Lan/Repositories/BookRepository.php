<?php

namespace Lan\Repositories;

use Ebs\Model\Book;
use Ebs\Model\Category as Ebs_Category;
use Ice\Core\DataSource;
use Ice\Core\Exception;
use Ice\Core\QueryResult;
use Ice\Exception\Config_Error;
use Ice\Exception\Error;
use Ice\Exception\FileNotFound;
use Ice\Exception\Security_User_NotFound;
use Ice\Helper\Date;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\AuthorRepositoryInterface;
use Lan\Contracts\Repositories\BookRepositoryInterface;
use Lan\Contracts\Repositories\UserRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\DataTypes\EbsCarbon;

class BookRepository implements BookRepositoryInterface
{
    public function __construct(
        private SecurityServiceInterface  $securityService,
        private AuthorRepositoryInterface $authorRepository,
        private UserRepositoryInterface $userRepository,
    )
    {

    }

    /**
     * @param DocumentsTypeRequestDTOInterface $requestDTO
     * @return QueryResult
     * @throws Exception
     * @throws Config_Error
     * @throws Error
     * @throws FileNotFound
     * @throws Security_User_NotFound
     * @throws \Throwable
     */
    public function getBooksFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult
    {
        $user = $this->securityService->getUser();
        $scopeOptions = [
            'user' => $user,
            'userId' => $user->getPkValue(),
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ($requestDTO->getCategoryId() === 0) ? ['denormal_private'] : ['denormal_dynamic', 'denormal_private', 'static', 'gift', 'extra', 'role', 'seb'],
            'mode' => 'default',
            //'allowBookNotActive' => true,
            'platform' => ['mobile'],
            'filters' => [],
            'fieldNames' => [
                'Publisher' => [
                    '/pk' => 'publisher_id',
                    'publisher_name' => 'publisher',
                ],
                'Book' => ['/pk' => 'id'],
                'Access' => [
                    'expired_date',
                    'access_id'
                ],
            ]
        ];

        if ($requestDTO->getCategoryId() !== 0) {
            $scopeOptions['filters']['Area_Knowledge'] = ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getCategoryId()];
            $scopeOptions['filters']['Available'] = ['Available.area_knowledge_pk=' . $requestDTO->getCategoryId()];
        }

        //фильтры в каталоге мобилки
        $bookFilters = [];

        if ($requestDTO->getSyntex()) {
            $bookFilters[] = 'Book.synthesizer_path IS NOT NULL';
        }

        if (!empty($requestDTO->getAuthor())) {
            $bookFilters[] = "Left(authors, 1)='" . DataSource::getInstance()->escapeString(mb_substr($requestDTO->getAuthor(), 0, 1)) . "'";
        }

        if ('0' === $requestDTO->getAuthor()) {
            $chars = $this->authorRepository->getAllCharacters();
            foreach ($chars as $bookKey => $bookItem) {
                $chars[$bookKey] = '\'' . $bookItem . '\'';
            }
            $bookFilters[] = "Left(authors, 1) NOT IN(" . implode(',', $chars) . ")";
        }

        if (!empty($bookFilters)) {
            $scopeOptions['filters'] = array_merge($scopeOptions['filters'], ['Book' => $bookFilters]);
        }

        if ($requestDTO->getPublisherId()) {
            $scopeOptions['filters'] = array_merge($scopeOptions['filters'], ['Publisher' => ['Publisher.id=' . $requestDTO->getPublisherId()]]);
        }

        if ($requestDTO->getSubCategoryID() && $requestDTO->getCategoryId() !== 0) {
            $row = Ebs_Category::createQueryBuilder()
                ->eq(['/pk' => $requestDTO->getSubCategoryId()])
                ->ne('area_knowledge__fk', $requestDTO->getSubCategoryId())
                ->ne('child_categories__json', '[]')
                ->getSelectQuery(['/pk', 'child_categories__json'], [], 'Ice\DataSource\Mysqli/front.ebs')
                ->getRow();
            if ($row) {
                $subCategoryArr = json_decode($row['child_categories__json'], true);
                $subCategoryArr[] = $requestDTO->getSubCategoryId();
                $scopeOptions['filters'] = array_merge($scopeOptions['filters'], ['Category' => ['Category.id IN(' . implode(',', $subCategoryArr) . ')']]);
            } else {
                $scopeOptions['filters'] = array_merge($scopeOptions['filters'], ['Category' => ['Category.id=' . $requestDTO->getSubCategoryId()]]);
            }
        }

        if ($requestDTO->getSubCategoryId() && $requestDTO->getCategoryId() === 0) {
            $scopeOptions['filters']['Area_Knowledge'] = ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getSubCategoryId()];
            $scopeOptions['filters']['Available'] = ['Available.area_knowledge_pk=' . $requestDTO->getSubCategoryId()];
        }

        if ($requestDTO->getCategoryId() === 0) {
            $queryBuilder = Book::createQueryBuilder()
                ->setCalcFoundRows()
                ->limit($requestDTO->getLimit(), $requestDTO->getOffset());
        } else {
            //после применения всех фильтров получаем
            $queryBuilder = Book::createQueryBuilder()
                ->setCalcFoundRows()
                ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
                ->desc('available');
        }

        if ('year' !== $requestDTO->getSortingField()) {
            $queryBuilder->asc('authors');
        } else {
            $queryBuilder->desc('publish_year');
        }
        $queryBuilder->asc('/name');


        $rowAddAccessTypeCallback = function ($row) {
            //Логика доступов
            $documentExpiredAt = EbsCarbon::parse($row['book_expired'])->toDateString();

            $userExpiredAtDate = EbsCarbon::parse($this->userRepository->getAuthenticatedUserExpiredDate())->toDateString();

            $access_date = $userExpiredAtDate < $documentExpiredAt ? $userExpiredAtDate : $documentExpiredAt;

            $isDocumentActive = (Date::get($access_date, 'Y-m-d H:i:s') > Date::get(null, 'Y-m-d H:i:s')) ? $row['available'] : false;
            $row['is_document_active'] = $isDocumentActive;
            $row['access_date'] = $access_date;

            return $row;
        };

        //для приватных книжек
        if ($requestDTO->getCategoryId() === 0) {
            $scopeOptions['fieldNames']['Available'] = [
                //'book_pk' => 'id'
            ];
            $rows = $queryBuilder
                ->scope('newAvailable', $scopeOptions)
                ->func(['' => 'available'], '1', '')
                ->group()
                ->afterSelectCallback($rowAddAccessTypeCallback)
                ->getSelectQuery([
                    '/pk' => 'id',
                    '/desc' => 'description',
                    'publish_year' => 'year',
                    '/expired',
                    '/name' => 'title',
                    'authors' => 'author',
                    'pdf_path' => 'hasPdf',
                    'epub_path' => 'hasEpub',
                    'audio_path' => 'hasAudio',
                    'synthesizer_path' => 'hasSyntex',
                    'synthesizer_editor',
                    'synthesizer_quality'
                ], [], 'Ice\DataSource\Mysqli/front.ebs')
                ->getQueryResult();
        } else {
            $rows = $queryBuilder
                ->scope('newAll', $scopeOptions)
                ->group()
                ->afterSelectCallback($rowAddAccessTypeCallback)
                ->getSelectQuery([
                    '/pk' => 'id',
                    '/desc' => 'description',
                    'publish_year' => 'year',
                    '/expired',
                    '/name' => 'title',
                    'authors' => 'author',
                    'pdf_path' => 'hasPdf',
                    'epub_path' => 'hasEpub',
                    'audio_path' => 'hasAudio',
                    'synthesizer_path' => 'hasSyntex',
                    'synthesizer_editor',
                    'synthesizer_quality'
                ], [], 'Ice\DataSource\Mysqli/front.ebs')
                ->getQueryResult();
        }

        return $rows;
    }

    /**
     * @param int $bookId
     * @return Book
     * @throws Config_Error
     * @throws Error
     * @throws Exception
     * @throws FileNotFound
     * @throws Security_User_NotFound
     */
    public function getBook(int $bookId): Book
    {
        $options = [
            'user' => $this->securityService->getUser(),
            'userId' => $this->securityService->getUser()->getPkValue(),
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['denormal_dynamic', 'denormal_private', 'static', 'gift', 'extra', 'role', 'seb'],
            'mode' => 'default',
            'platform' => ['mobile'],
            'allowBookNotActive' => true,
            'filters' => [
                'Book' => ['Book.id=' . $bookId]
            ],
            'fieldNames' => [
                'Book' => ['/pk', '/name', '/desc', '/active', '/expired', 'authors', 'isbn', 'edition', 'pages', 'publish_year', 'publisher__fk', 'pdf_path', 'epub_path', 'file_path', 'audio_path', 'synthesizer_path', 'synthesizer_editor', 'synthesizer_quality'],
                'Access' => ['expired_date', 'access_type__fk'],
                'Publisher' => ['/name']
            ]
        ];

        foreach (['filters', 'fieldNames'] as $option) {
            if (isset($extOptions[$option])) {
                foreach ($extOptions[$option] as $tableAlias => $params) {
                    if (isset($options[$option][$tableAlias])) {
                        $options[$option][$tableAlias] = array_merge($options[$option][$tableAlias], (array)$params);
                    } else {
                        $options[$option][$tableAlias] = (array)$params;
                    }
                }
            }
        }

        $book = Book::createQueryBuilder()
            ->scope('newAll', $options)
            ->group()
            ->getSelectQuery(null)
            ->getModel();

        if (!$book) {
            return Book::create();
        }

        // make as web ebs
        $otherEditions = $book->getOtherEditions(array_merge($options, [
            $book->getPkValue() => array_merge(
                $book->get(),
                $book->getRaw()
            )
        ]));

        foreach ($otherEditions as $edition) {
            if ($edition['available']) {
                $book->set([
                    'available' => 1
                ]);
            }
        }

        //Логика доступов
        $documentExpiredAt = EbsCarbon::parse($book->get('book_expired'))->toDateString();

        $userExpiredAtDate = EbsCarbon::parse($this->userRepository->getAuthenticatedUserExpiredDate())->toDateString();

        $access_date = $userExpiredAtDate < $documentExpiredAt ? $userExpiredAtDate : $documentExpiredAt;

        $isDocumentActive = (Date::get($access_date, 'Y-m-d H:i:s') > Date::get(null, 'Y-m-d H:i:s')) ? $book->get('available') : false;

        $book->set('active', $isDocumentActive);
        $book->set('access_date', $access_date);

        return $book;
    }
}
