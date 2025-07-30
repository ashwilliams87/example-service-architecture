<?php


namespace Tests\Api\Journals;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class ArticleMetaCest extends BaseAuthenticatedCert
{
    private string $endpointUrl;
    protected int $existingArticleId = 162254;
    protected int $nonExistingArticleId = 999999999;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/article/meta';
    }

    public function testRequestWithWrongAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, ['id' => $this->existingArticleId]);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, ['id' => $this->existingArticleId]);
        $apiTester->seeResponseCodeIs(200);
    }

    public function testRequestWithValidAuthTokenForNotExistBookAndCheckStatusCode(ApiTester $apiTester): void
    {
        $this->setValidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, ['id' => $this->nonExistingArticleId]);
        $apiTester->seeResponseCodeIs(404);
    }
}
