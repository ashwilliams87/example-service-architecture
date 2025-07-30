<?php


namespace Tests\Api\Categories;

use Lan\Enums\DocumentType;
use Tests\Api\BaseAuthenticatedCert;
use Tests\Support\ApiTester;

class CategoryListCest extends BaseAuthenticatedCert
{
    protected string $endpointUrl;
    protected array $requestParams = [
        'syntex' => 0,
        'limit' => 15,
        'offset' => 0,
    ];

    protected $categoryTypes = [
        DocumentType::BOOK->value,
        DocumentType::JOURNAL->value,
        ''
    ];

    public function _before(ApiTester $apiTester): void
    {
        parent::_before($apiTester);
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/categories/';
    }

    public function testRequestWithWrongAuthToken(ApiTester $apiTester): void
    {
        $this->setInvalidAuthHeaders($apiTester);
        $apiTester->sendGet($this->endpointUrl, $this->requestParams);
        $apiTester->seeResponseCodeIs(401);
    }

    public function testAllCategoriesTypeRequestsWithValidAuthTokenAndCheckStatusCode(ApiTester $apiTester): void
    {
        foreach ($this->categoryTypes as $categoryType){
            $this->setValidAuthHeaders($apiTester);
            $params = array_merge($this->requestParams, ['type' => $categoryType]);
            $apiTester->sendGet($this->endpointUrl, $params);
            $apiTester->seeResponseCodeIs(200);
        }
    }
}
