<?php


namespace Tests\Api\Sync;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class SyncAllCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;
    protected array $requestParams;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/sync/all';
        $this->requestParams = [
            'data' => [
                'bookMarks' => [],
                'books' => [],
                'journalArticles' => [],
            ],
            'device_time' => time(),
        ];
    }

    public function testRequestWithWrongAuthToken(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendPost($this->endpointUrl, $this->requestParams);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendPost($this->endpointUrl, $this->requestParams);
        $apiTester->seeResponseCodeIs(200);
    }
}
