<?php


namespace Tests\Api\Auth;

use Tests\Support\ApiTester;

class CheckIpCest
{
    protected string $endpointUrl;

    public function _before(ApiTester $apiTester): void
    {
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $this->endpointUrl = env('API_TESTS_REQUEST_BASE_URL') . '/ebs/1.1/users/check_ip';
    }

    public function testCheckIpRequest(ApiTester $apiTester): void
    {

        $apiTester->sendGet($this->endpointUrl);
        $response = $apiTester->grabResponse();

        $expectedErrorResponse = '{"code":401,"error":{"message":"Ip не найден"},"status":"error","type":"object","data":[]}';

        if ($response === $expectedErrorResponse) {
            return;
        }

        $expectedSuccessResponsePattern = '/{"code":200,"error":"","status":"OK","type":"object","data":{"subscriber_name":"[^"]+"}}/';
        if(preg_match($expectedSuccessResponsePattern, $response)){
           return;
        }

        $apiTester->failTest();
    }
}
