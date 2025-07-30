<?php


namespace Tests\Api\Publishers;

use Lan\Enums\DocumentType;
use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class PublisherListCardCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;
    protected array $documentTypeList = [
        DocumentType::BOOK->value,
        DocumentType::JOURNAL->value,
        ''
    ];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/publishers';
    }

    public function testRequestWithWrongTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->documentTypeList as $documentType){
            $this->setInvalidAuthHeaders($apiTester);
            $apiTester->sendGet($this->endpointUrl, [
                'catId' => 917,
                'limit' => 100,
                'offset' => 0,
                'publisherId' => '',
                'subCatId' => '',
                'type' => $documentType
            ]);
            $apiTester->seeResponseCodeIs(401);
        }
    }

    public function testRequestWithValidTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->documentTypeList as $documentType) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet($this->endpointUrl, [
                'catId' => 917,
                'limit' => 100,
                'offset' => 0,
                'publisherId' => '',
                'subCatId' => '',
                'type' => $documentType
            ]);
            $apiTester->seeResponseCodeIs(200);
        }
    }

    public function testRequestPrivatePublisherListWithValidTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->documentTypeList as $documentType) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet($this->endpointUrl, [
                'catId' => 0, // Для запроса приватных данных требуется передать 0
                'limit' => 100,
                'offset' => 0,
                'publisherId' => '',
                'subCatId' => '',
                'type' => $documentType
            ]);
            $apiTester->seeResponseCodeIs(200);
        }
    }
}
