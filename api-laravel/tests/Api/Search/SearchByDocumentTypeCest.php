<?php


namespace Api\Search;

use Lan\Contracts\Repositories\SearchRepositoryInterface;
use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class SearchByDocumentTypeCest extends BaseAuthenticatedCert
{
    protected array $endpointUrlList = [];
    protected array $routeList = [
        '/ebs/1.1/search/books',
        '/ebs/1.1/search/journals',
        '/ebs/1.1/search/articles',
    ];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        foreach ($this->routeList as $route) {
            $this->endpointUrlList[] = env('API_TESTS_REQUEST_BASE_URL') . $route;
        }
    }

    public function testRequestWithWrongAuthToken(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        foreach ($this->endpointUrlList as $endpointUrl) {
            $apiTester->sendGet($endpointUrl, [
                'type' => 2,
                'query' => 'test',
                'category' => 945,
                'syntex' => 0,
                'limit' => 10,
                'offset' => 0,
            ]);
            $apiTester->seeResponseCodeIs(401);
        }
    }

    public function testAllSearchTypesWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $searchTypeIds = array_merge(
            SearchRepositoryInterface::BOOK_SEARCH_TYPE_LIST,
            SearchRepositoryInterface::JOURNAL_SEARCH_TYPE_LIST,
            SearchRepositoryInterface::ARTICLE_SEARCH_TYPE_LIST,
        );

        $this->setValidAuthHeaders($apiTester);
        foreach ($this->endpointUrlList as $endpointUrl) {
            foreach ($searchTypeIds as $searchTypeId) {
                $apiTester->sendGet($endpointUrl, [
                    'type' => $searchTypeId,
                    'query' => 'тест',
                    'category' => 945,
                    'syntex' => 0,
                    'limit' => 10,
                    'offset' => 0,
                ]);
                $apiTester->seeResponseCodeIs(200);
            }
        }
    }

    public function testNonExistSearchTypeWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->endpointUrlList as $endpointUrl) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet($endpointUrl, [
                'type' => 9999,
                'query' => 'тест',
                'category' => 945,
                'syntex' => 0,
                'limit' => 10,
                'offset' => 0,
            ]);
            $apiTester->seeResponseCodeIs(400);
        }
    }

    public function testBookSearchWithValidAuthTokenAndCheckForDataStructure(ApiTester $apiTester): void
    {
        $endpointUrl = '/ebs/1.1/search/books';
        foreach (SearchRepositoryInterface::BOOK_SEARCH_TYPE_LIST as $searchType) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet(env('API_TESTS_REQUEST_BASE_URL') . $endpointUrl, [
                'type' => $searchType,
                'query' => 'тест',
                'category' => '',
                'syntex' => 0,
                'limit' => 15,
                'offset' => 0,
            ]);
            $apiTester->seeResponseCodeIs(200);

            $apiTester->seeResponseMatchesJsonType([
                'code' => 'integer',
                'error' => 'string',
                'status' => 'string',
                'type' => 'string',
                'data' => 'array',
            ]);

            $apiTester->seeResponseMatchesJsonType([
                'word' => 'string',
                'snippet' => 'string',
                'id' => 'integer',
                'hasPdf' => 'boolean',
                'hasEpub' => 'boolean',
                'hasAudio' => 'boolean',
                'hasSyntex' => 'boolean',
                'book_expired' => 'string',
                'book_private' => 'integer',
                'synthesizer_editor' => 'string',
                'synthesizer_quality' => 'integer',
                'active' => 'boolean',
                'title' => 'string',
                'author' => 'string',
                'publisher' => 'string',
            ], '$.data.*');
        }
    }

    public function testArticleSearchWithValidAuthTokenAndCheckForDataStructure(ApiTester $apiTester): void
    {
        $endpointUrl = '/ebs/1.1/search/articles';
        foreach (SearchRepositoryInterface::ARTICLE_SEARCH_TYPE_LIST as $articleType) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet(env('API_TESTS_REQUEST_BASE_URL') . $endpointUrl, [
                'type' => $articleType,
                'query' => 'тест',
                'category' => '',
                'syntex' => 0,
                'limit' => 15,
                'offset' => 0,
            ]);
            $apiTester->seeResponseCodeIs(200);

            $apiTester->seeResponseMatchesJsonType([
                'code' => 'integer',
                'error' => 'string',
                'status' => 'string',
                'type' => 'string',
                'data' => 'array',
            ]);

            $apiTester->seeResponseMatchesJsonType([
                'id' => 'integer',
                'snippet' => 'string',
                'start_page' => 'integer',
                'finish_page' => 'integer',
                'pages' => 'string',
                'active' => 'boolean',
                'title' => 'string',
                'author' => 'string',
                'publisher' => 'string',
                'journalId' => 'integer',
                'journalName' => 'string',
                'issue' => 'string',
                'year' => 'integer',
                'description' => 'string',
            ], '$.data.*');
        }
    }

    public function testJournalSearchWithValidAuthTokenAndCheckForDataStructure(ApiTester $apiTester): void
    {
        $endpointUrl = '/ebs/1.1/search/journals';
        foreach (SearchRepositoryInterface::JOURNAL_SEARCH_TYPE_LIST as $journalType) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet(env('API_TESTS_REQUEST_BASE_URL') . $endpointUrl, [
                'type' => $journalType,
                'query' => 'science',
                'category' => '',
                'syntex' => 0,
                'limit' => 15,
                'offset' => 0,
            ]);
            $apiTester->seeResponseCodeIs(200);

            $apiTester->seeResponseMatchesJsonType([
                'code' => 'integer',
                'error' => 'string',
                'status' => 'string',
                'type' => 'string',
                'data' => 'array',
            ]);

            $apiTester->seeResponseMatchesJsonType([
                'word' => 'string',
                'snippet' => 'string',
                'id' => 'integer',
                'publisher' => 'string',
                'active' => 'boolean',
                'title' => 'string',
                'cover' => 'string',
            ], '$.data.*');
        }
    }
}
