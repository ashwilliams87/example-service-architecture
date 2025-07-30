<?php

namespace Tests\Unit\RequestResults;

use Codeception\Test\Unit;
use Lan\DataTypes\RequestResult\Error\Unauthorized;

class UnauthorizedTest extends Unit
{

    public function testGetStatusCode(): void
    {
        $requestResult = Unauthorized::create();

        $this->assertEquals(401, $requestResult->getStatusCode());
        $this->assertEquals('Пользователь не авторизован', $requestResult->getMessage());

    }

    public function testGetMessage(): void
    {
        $requestResult = Unauthorized::create(message: 'Нельзя!');

        $this->assertEquals(401, $requestResult->getStatusCode());
        $this->assertEquals('Нельзя!', $requestResult->getMessage());
    }
}
