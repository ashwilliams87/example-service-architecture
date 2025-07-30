<?php

namespace Tests\Unit\RequestResults;

use Lan\DataTypes\RequestResult\HttpResult;
use Codeception\Test\Unit;

class HttpResultTest extends Unit
{
    public function testHttpResultDefault(): void
    {
        $result = HttpResult::create();
        $this->assertEquals($result->getMessage(), 'I’m a teapot, but programmer are lame.');
        $this->assertEquals($result->getStatusCode(), 418);

        $result204 = HttpResult::create(statusCode: 204);

        $this->assertEquals($result204->getMessage(), 'I’m a teapot, but programmer are lame.');
        $this->assertEquals($result204->getStatusCode(), 204);

        $result204Text = HttpResult::create('Text', 204);
        $this->assertEquals($result204Text->getMessage(), 'Text');
        $this->assertEquals($result204Text->getStatusCode(), 204);

        $result204Text = HttpResult::create('Text', 204);
        $this->assertNotEquals($result204Text->isError(), false);
    }

}
