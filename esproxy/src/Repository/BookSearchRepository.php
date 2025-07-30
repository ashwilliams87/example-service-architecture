<?php

namespace App\Repository;

use App\Contract\DTO\Request\DocumentContentSearchDTOInterface;
use App\Contract\DTO\Request\DocumentSearchDTOInterface;
use App\Contract\Repository\BookSearchRepositoryInterface;
use App\Contract\Service\SecurityServiceInterface;
use App\Enum\ElasticIndexName;
use Elastica\Client as ElasticaClient;
use Elastica\Collapse;
use Elastica\Index;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use Elastica\Query\Terms;
use Elastica\ResultSet;
use Ice\Core\Debuger;

class BookSearchRepository implements BookSearchRepositoryInterface
{
    private Index $bookIndex;

    public function __construct(
        private readonly ElasticaClient           $elasticaClient,
        private readonly SecurityServiceInterface $securityService,
    )
    {
        $this->bookIndex = $this->elasticaClient->getIndex(ElasticIndexName::BOOKS->value);
    }

    public function searchBooks(DocumentSearchDTOInterface $searchQuery): ResultSet
    {
        $boolQuery = new BoolQuery();

        $contentQuery = new MatchQuery();
        $contentQuery->setFieldQuery('page_content', $searchQuery->getSearchQuery());

        $boolQuery->addMust($contentQuery);

        $boolQuery->addMust(new Terms('subscribers', [$searchQuery->getSubscriberId()]));

        $query = new Query($boolQuery);

        $collapse = (new Collapse())->setFieldname('book_id');
        $query->setCollapse($collapse);
        $query->setSize($searchQuery->getLimit());

        return $this->bookIndex->search($query);
    }

    public function searchBookContent(DocumentContentSearchDTOInterface $documentContentSearchDTO): ResultSet
    {
        $boolQuery = new BoolQuery();

        $boolQuery->addMust(new Terms('subscribers', [$this->securityService->getSubscriberId()]));

        if (!empty($documentContentSearchDTO->getBookIds())) {
            $boolQuery->addFilter(new Terms('book_id', $documentContentSearchDTO->getBookIds()));
        }

        $contentQuery = new MatchQuery();
        $contentQuery->setFieldQuery('page_content', $documentContentSearchDTO->getSearchQuery());
        $contentQuery->setFieldMinimumShouldMatch('page_content', $documentContentSearchDTO->getSimilarityCutoff() * 100 . '%');

        $boolQuery->addMust($contentQuery);

        $mainQuery = new Query($boolQuery);
        $mainQuery->setSize($documentContentSearchDTO->getLimit());

        $mainQuery->setHighlight([
            'fields' => [
                'page_content' => new \stdClass(), // можно указать параметры, если надо
            ],
        ]);

        $result = $this->bookIndex->search($mainQuery);
        return $result;
    }
}