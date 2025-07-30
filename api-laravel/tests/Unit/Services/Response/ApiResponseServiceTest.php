<?php

namespace Tests\Unit\Services\Response;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Illuminate\Http\Response;
use Lan\Services\Response\ApiResponseBuilder;
use Lan\Services\Response\ApiResponseService;

class ApiResponseServiceTest extends Unit
{
    public function testEmptySuccessResponse() : void
    {
        $apiResponseBuilderMock = $this->makeEmpty(ApiResponseBuilder::class, [
            'setStatusCode' => Expected::once(function (int $statusCode) use (&$apiResponseBuilderMock) {
                self::assertEquals(200, $statusCode);
                return $apiResponseBuilderMock;
            }),
            'setOkStatus' => Expected::once(),
            'setNoneType' => Expected::once(),
            'getResponse' => Expected::once(function () {
                return (new Response())->setStatusCode(200);
            }),
        ]);

        $response = (new ApiResponseService($apiResponseBuilderMock))
            ->makeEmptySuccessResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
