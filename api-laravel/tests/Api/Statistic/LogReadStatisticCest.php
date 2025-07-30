<?php


namespace Tests\Api\Statistic;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class LogReadStatisticCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;
    protected array $requestPayload = [
        'statistics' => [
            [
                'entity' => "book",
                'entity_id' => 195479,
                'page' => 20,
            ],
            [
                'entity' => "journalArticle",
                'entity_id' => 599632,
                'page' => 10,
            ]
        ]
    ];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/stat/read';
    }

    public function testRequestWithWrongAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendPost($this->endpointUrl, $this->requestPayload);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendPost($this->endpointUrl, $this->requestPayload);
        $apiTester->seeResponseCodeIs(200);
    }
}
