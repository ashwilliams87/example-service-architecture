<?php


namespace Api\Auth;

use Tests\Support\ApiTester;

class RecoverPasswordCest
{
    protected string $endpointUrl;

    public function _before(ApiTester $apiTester): void
    {
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->endpointUrl =  env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/users/recovery';
    }

    public function testRecoverPasswordWithValidEmailAndCheckForSuccessStatusCode(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->endpointUrl, [
            'email' => env('RECOVERABLE_USER_EMAIL'),
        ]);

        $apiTester->seeResponseCodeIs(200);
    }

    public function testRecoverPasswordWithEmptyEmailAndCheckForSuccessStatusCode(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->endpointUrl, [
            'email' => '',
        ]);

        $apiTester->seeResponseCodeIs(400);
    }

    public function testRecoverPasswordWithNonExistEmailAndCheckForSuccessStatusCode(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->endpointUrl, [
            'email' => '9a9a9a9a9a9as9d9129dh97812hd@nonexist.com',
        ]);

        $apiTester->seeResponseCodeIs(400);
    }

    public function testNonRecoverableLibraryUserEmail(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->endpointUrl, [
            'email' => env('NON_RECOVERABLE_LIBRARY_USER_EMAIL'),
        ]);

        $apiTester->seeResponseCodeIs(400);
    }
}
