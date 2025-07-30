<?php


namespace Tests\Api\Auth;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class RegisterByEmailCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl =  env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/users/register';
    }

    public function testRegisterByEmailWithNoNames(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->endpointUrl, [
            'email' => 'john.doe@example.com',
            'password' => '12345678',
            'name' => ''
        ]);
        $apiTester->seeResponseCodeIs(400);
    }
}
