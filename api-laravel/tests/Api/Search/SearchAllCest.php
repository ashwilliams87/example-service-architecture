<?php


namespace Tests\Api\Search;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class SearchAllCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;

    protected array $requestParams = [
        'query' => 'Основы',
        'category' => 945,
        'syntex' => 1,
    ];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/search';
    }

    public function testRequestWithWrongAuthToken(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, $this->requestParams);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testSearchAllWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $params = array_merge($this->requestParams);
        $apiTester->sendGet($this->endpointUrl, $params);
        $apiTester->seeResponseCodeIs(200);
    }

    public function testSearchAllWithValidAuthTokenAndCheckResponseStructure(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $params = array_merge($this->requestParams);
        $apiTester->sendGet($this->endpointUrl, $params);

        $apiTester->seeResponseMatchesJsonType([
            'code' => 'integer',
            'error' => 'string',
            'status' => 'string',
            'type' => 'string',
            'data' => [
                'data' => [
                    [
                        'id' => 'integer',
                        'type' => 'string',
                        'title' => 'string',
                        'cnt' => 'string'
                    ]
                ],
                'cnt' => 'integer'
            ]
        ]);
    }
}
