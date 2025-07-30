<?php


namespace Tests\Api\Books;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class BookCardCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;
    protected int $existingBookId = 183134;
    protected int $nonExistingBookId = 999999999;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/book';
    }

    public function testRequestWithWrongTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'id' => $this->existingBookId
        ]);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidTokenToExistingBookAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'id' => $this->existingBookId
        ]);
        $apiTester->seeResponseCodeIs(200);
    }

    public function testRequestWithValidTokenToNonExistingBookAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, [
            'id' => $this->nonExistingBookId
        ]);
        $apiTester->seeResponseCodeIs(404);
    }
}
