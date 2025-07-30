<?php


namespace Api\Auth;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class LogOutCest extends BaseAuthenticatedCert
{
    protected array $endpointUrlList = [];
    protected array $routeList = [
        '/ebs/1.1/users/current',
        '/ebs/1.1/users/current/social',
    ];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        foreach ($this->routeList as $route) {
            $this->endpointUrlList[] = env('API_TESTS_REQUEST_BASE_URL') . $route;
        }
    }

    public function testLogoutWithValidTokenAndCheckForSuccessStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->endpointUrlList as $endpointUrl) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendDelete($endpointUrl);
            $apiTester->seeResponseCodeIs(200);
        }
    }
}
