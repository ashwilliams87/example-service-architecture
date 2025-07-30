<?php


namespace Tests\Api\Authors;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class AuthorCharacterMapCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/authors';
    }

    public function testRequestWithWrongTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'catId' => 917,
            'limit' => 100,
            'offset' => 0,
            'publisherId' => '',
            'subCatId' => ''
        ]);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'catId' => 917,
            'limit' => 100,
            'offset' => 0,
            'publisherId' => '',
            'subCatId' => ''
        ]);
        $apiTester->seeResponseCodeIs(200);
    }

    public function testRequestPrivateAuthorCharacterMapWithValidTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'catId' => 0, // Для запроса приватных данных требуется передать 0
            'limit' => 100,
            'offset' => 0,
            'publisherId' => '',
            'subCatId' => ''
        ]);
        $apiTester->seeResponseCodeIs(200);
    }
}
