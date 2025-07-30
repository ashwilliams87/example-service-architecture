<?php


namespace Tests\Api;

use Tests\Support\ApiTester;

class BaseAuthenticatedCert
{
    protected string $validToken;
    protected string $logInUrl;

    public function _before(ApiTester $apiTester): void
    {
        $this->logInUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/users/current';
        $this->validToken = $this->getValidAuthToken($apiTester);
    }

    private function getValidAuthToken(ApiTester $apiTester): string
    {
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $apiTester->sendPost($this->logInUrl, [
            'email' => env('TEST_USER_EMAIL'),
            'password' => env('TEST_USER_PASSWORD')
        ]);

        return $this->validToken = $apiTester->grabDataFromResponseByJsonPath('$.data.User.x-auth-token')[0];
    }

    protected function setValidAuthHeaders(ApiTester $apiTester): void
    {
        $apiTester->haveHttpHeader('X-Auth-Token', $this->validToken);
        $apiTester->haveHttpHeader('X-Device-Id', $this->extractUniqueUserFromToken($this->validToken));
    }

    protected function setInvalidAuthHeaders(ApiTester $apiTester): void
    {
        $apiTester->haveHttpHeader('X-Auth-Token', 'InvalidToken');
        $apiTester->haveHttpHeader('X-Device-Id', 'InvalidDeviceId');
    }

    private function extractUniqueUserFromToken(string $token): string
    {
        $tokenParts = explode('.', $token);
        $payload = base64_decode($tokenParts[1]);
        $payloadJson = json_decode($payload, true);

        return $payloadJson['unique_user'];
    }
}
