<?php


namespace Tests\Api\Journals;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class JournalListCest extends BaseAuthenticatedCert
{
    private string $endpointUrl;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/journals';
    }

    public function testRequestWithWrongAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'category' => 917,
            'limit' => 10,
            'offset' => 0,
        ]);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'category' => 917,
            'limit' => 10,
            'offset' => 0,
        ]);
        $apiTester->seeResponseCodeIs(200);
    }
}
