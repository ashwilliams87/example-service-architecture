<?php


namespace Tests\Api\Categories;

use Lan\Enums\DocumentType;
use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class SubCategoryListCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;
    protected $categoryTypes = [
        DocumentType::BOOK->value,
        DocumentType::JOURNAL->value,
    ];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/subcategories';
    }

    public function testRequestWithWrongAuthToken(ApiTester $apiTester): void
    {
        foreach ($this->categoryTypes as $categoryType) {
            $this->setInvalidAuthHeaders($apiTester);
            $apiTester->sendGet($this->endpointUrl, [
                'type' => $categoryType,
                'syntex' => 0,
                'limit' => 3,
                'offset' => 10,
                'catId' => 917
            ]);
            $apiTester->seeResponseCodeIs(401);
        }
    }

    public function testAllCategoriesTypeRequestsWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->categoryTypes as $categoryType) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet($this->endpointUrl, [
                'type' => $categoryType,
                'syntex' => 0,
                'limit' => 3,
                'offset' => 10,
                'catId' => 917
            ]);
            $apiTester->seeResponseCodeIs(200);
        }
    }

    public function testAllCategoriesTypeRequestsWithSyntexEnabledAndValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->categoryTypes as $categoryType) {
            $this->setValidAuthHeaders($apiTester);
            $apiTester->sendGet($this->endpointUrl, [
                'type' => $categoryType,
                'syntex' => 1,
                'limit' => 3,
                'offset' => 10,
                'catId' => 917
            ]);
            $apiTester->seeResponseCodeIs(200);
        }
    }
}
