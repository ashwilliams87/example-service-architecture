<?php


namespace Tests\Api\Books;

use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class DownloadBookCest extends BaseAuthenticatedCert
{
    private array $endpointUrlList = [];
    protected int $existingBookId = 192702;

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $fileTypeList = ['pdf', 'epub', 'text', 'audio'];
        foreach ($fileTypeList as $fileType) {
            $this->endpointUrlList[] = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/book/' . $fileType;
        }
    }

    public function testRequestsWithWrongAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->endpointUrlList as $bookDownloadEndpointUrl) {
            $this->setInvalidAuthHeaders($apiTester);
            $apiTester->sendGet($bookDownloadEndpointUrl, ['id' => $this->existingBookId]);
            $apiTester->seeResponseCodeIs(401);
        }
    }

    public function testRequestWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->endpointUrlList as $bookDownloadEndpointUrl) {
            $this->setInvalidAuthHeaders($apiTester);
            $apiTester->sendGet($bookDownloadEndpointUrl, ['id' => $this->existingBookId]);
            $apiTester->seeResponseCodeIs(401);
        }
    }
}
