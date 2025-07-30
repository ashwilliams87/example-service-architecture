<?php

namespace Lan\Repositories;

use Ebs\Model\Book;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\AuthorRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function __construct(
        private SecurityServiceInterface $securityService,
    )
    {

    }

    private array $characterMap = [
        'rus' => [
            'А' => false, 'Б' => false, 'В' => false, 'Г' => false, 'Д' => false,
            'Е' => false, 'Ж' => false, 'З' => false, 'И' => false, 'К' => false,
            'Л' => false, 'М' => false, 'Н' => false, 'О' => false, 'П' => false,
            'Р' => false, 'С' => false, 'Т' => false, 'У' => false, 'Ф' => false,
            'Х' => false, 'Ц' => false, 'Ч' => false, 'Ш' => false, 'Щ' => false,
            'Э' => false, 'Ю' => false, 'Я' => false
        ],
        'eng' => [
            'A' => false, 'B' => false, 'C' => false, 'D' => false, 'E' => false,
            'F' => false, 'G' => false, 'H' => false, 'I' => false, 'J' => false,
            'K' => false, 'L' => false, 'M' => false, 'N' => false, 'O' => false,
            'P' => false, 'Q' => false, 'R' => false, 'S' => false, 'T' => false,
            'U' => false, 'V' => false, 'W' => false, 'X' => false, 'Y' => false,
            'Z' => false
        ],
        'num' => [
            '0-9' => false
        ]
    ];

    public function getAllCharacters(): array
    {
        return array_keys($this->characterMap['rus']) + array_keys($this->characterMap['eng']);
    }

    public function getCharacterMap(): array
    {
        return $this->characterMap;
    }

    public function getAuthorCharacterList(DocumentsTypeRequestDTOInterface $requestDTO): array
    {
        $user = $this->securityService->getUser();
        $scopeOptions = [
            'user' => $user,
            'userId' => $user->getPkValue(),
            'subscriber' => $user->getSubscriber(),
            'packets' => ['denormal_dynamic', 'denormal_private', 'static', 'gift', 'extra', 'role', 'seb'],
            'mode' => 'default',
            'platform' => ['mobile'],
            'fieldNames' => [
                'Available' => ['authors'],
                'Book' => ['/pk' => 'id', 'authors', 'access_id']
            ]
        ];

        if ($requestDTO->getCategoryId()) {
            $scopeOptions['filters']['Area_Knowledge'] = ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getCategoryId()];
        }

        if ($requestDTO->getSyntex()) {
            $scopeOptions['filters']['Book'] = ['Book.synthesizer_path IS NOT NULL'];
        }

        if ($requestDTO->getPublisherId()) {
            $scopeOptions['filters']['Publisher'] = ['Publisher.id=' . $requestDTO->getPublisherId()];
        }

        if ($requestDTO->getSubCategoryId()) {
            $scopeOptions['filters']['Category'] = ['Category.id=' . $requestDTO->getSubCategoryId()];
        }

        return Book::createQueryBuilder()
            ->scope('newAll', $scopeOptions)
            ->func(['LEFT' => 'char'], 'authors, 1')
            ->group('`char`')
            ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
            ->having('`char`<>\'\' ')
            ->getSelectQuery(null, null, 'Ice\DataSource\Mysqli/front.ebs')
            ->getColumn('char');
    }

    public function getAuthorPrivateCharacterList(DocumentsTypeRequestDTOInterface $requestDTO): array
    {

        $scopeOptions = [
            'subscriber' => $this->securityService->getSubscriber(),
            'packets' => ['private'],
            'platform' => ['mobile'],
            'filters' => [],
            'fieldNames' => [
                'Available' => ['/pk' => 'id', 'authors']
            ]
        ];

        if ($requestDTO->getCategoryId()) {
            $scopeOptions['filters']['Area_Knowledge'] = ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getCategoryId()];
        }

        if ($requestDTO->getSyntex()) {
            $scopeOptions['filters']['Available'] = ['Book.synthesizer_path IS NOT NULL'];

        }

        if ($requestDTO->getPublisherId()) {
            $scopeOptions['filters']['Publisher'] = ['Publisher.id=' . $requestDTO->getPublisherId()];
        }

        if ($requestDTO->getSubCategoryId()) {
            $scopeOptions['filters']['Area_Knowledge'] = ['Area_Knowledge.area_knowledge_id=' . $requestDTO->getSubCategoryId()];
        }

        return Book::createQueryBuilder()
            ->scope('newAvailable', $scopeOptions)
            ->func(['LEFT' => 'char'], 'authors, 1')
            ->group('`char`')
            ->limit($requestDTO->getLimit(), $requestDTO->getOffset())
            ->having('`char`<>\'\' ')
            ->getSelectQuery(null, null, 'Ice\DataSource\Mysqli/front.ebs')
            ->getColumn('char');
    }
}
