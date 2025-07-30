<?php


namespace Tests\Api\Journals;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class ArticleListCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;
    protected array $requestParams = ['id' => 292672];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/articles';
    }

    public function testRequestWithWrongAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, $this->requestParams);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, $this->requestParams);
        $apiTester->seeResponseCodeIs(200);
    }
}
