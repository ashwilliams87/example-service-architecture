<?php


namespace Tests\Api\Auth;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class DeactivateUserCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->endpointUrl =  env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/users/current/social/delete';
    }
    public function testRequestWithWrongAuthToken(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendPut($this->endpointUrl);
        $apiTester->seeResponseCodeIs(403); // Wrong Token = Guest token
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendPut($this->endpointUrl);
        $apiTester->seeResponseCodeIs(200);
    }
}
