<?php


namespace Tests\Api\Journals;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class DownloadArticleCest extends BaseAuthenticatedCert
{
    private array $endpointUrlList = [];
    protected int $existingArticleId = 162254;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $fileTypeList = ['pdf', 'epub', 'text', 'audio'];
        foreach ($fileTypeList as $fileType) {
            $this->endpointUrlList[] = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/article/' . $fileType;
        }
    }

    public function testRequestsWithWrongAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->endpointUrlList as $bookDownloadEndpointUrl) {
            $this->setInvalidAuthHeaders($apiTester);
            $apiTester->sendGet($bookDownloadEndpointUrl, ['id' => $this->existingArticleId]);
            $apiTester->seeResponseCodeIs(401);
        }
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->endpointUrlList as $bookDownloadEndpointUrl) {
            $this->setInvalidAuthHeaders($apiTester);
            $apiTester->sendGet($bookDownloadEndpointUrl, ['id' => $this->existingArticleId]);
            $apiTester->seeResponseCodeIs(401);
        }
    }
}
