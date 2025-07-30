<?php


namespace Tests\Api\Auth;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class LogInByEmailCest extends BaseAuthenticatedCert
{
    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
    }

    public function testLoginWithValidCredentialsAndCheckForSuccessStatusCode(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->logInUrl, [
            'email' => env('TEST_USER_EMAIL'),
            'password' => env('TEST_USER_PASSWORD')
        ]);

        $apiTester->seeResponseCodeIs(200);
    }

    public function testLoginWithValidCredentialsAndCheckForAuthToken(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->logInUrl, [
            'email' => env('TEST_USER_EMAIL'),
            'password' => env('TEST_USER_PASSWORD')
        ]);

        $token = $apiTester->grabDataFromResponseByJsonPath('$.data.User.x-auth-token')[0];
        $apiTester->amBearerAuthenticated($token);
    }

    public function testLoginWithWrongPassword(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->logInUrl, [
            'email' => env('TEST_USER_EMAIL'),
            'password' => 'wrong_password'
        ]);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testLoginWithNotExistingAccount(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->logInUrl, [
            'email' => 'alaksdfjalsdk1214asdlkfajsdlfdjlkfajdlfak@yandex.ru',
            'password' => 'wrong_password'
        ]);
        $apiTester->seeResponseCodeIs(401);
    }
}
