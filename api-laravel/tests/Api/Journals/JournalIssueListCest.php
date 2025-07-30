<?php


namespace Tests\Api\Journals;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class JournalIssueListCest extends BaseAuthenticatedCert
{
    private string $endpointUrl;
    protected int $existingJournalId = 2374;
    protected int $nonExistingJournalId = 999999999;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/issue';
    }

    public function testRequestWithWrongAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, ['id' => $this->existingJournalId]);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, ['id' => $this->existingJournalId]);
        $apiTester->seeResponseCodeIs(200);
    }

    public function testRequestWithValidAuthTokenForNotExistJournalAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, ['id' => $this->nonExistingJournalId]);
        $apiTester->seeResponseCodeIs(404);
    }
}
