<?php


namespace Tests\Api\Auth;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class RegisterBySocialCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl =  env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/users/register/social';
    }

    public function testRegisterBySocialWithEmptyInviteCode(ApiTester $apiTester): void
    {
        $apiTester->sendPost($this->endpointUrl, [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'securepassword123',
            'network' => 'facebook',
            'token_social' => 'fake_social_token',
            'inviteCode' => '',
        ]);
        $apiTester->seeResponseCodeIs(405);
    }
}
