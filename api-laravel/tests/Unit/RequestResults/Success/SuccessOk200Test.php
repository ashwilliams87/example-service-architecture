<?php

namespace Tests\Unit\RequestResults\Success;

use Codeception\Test\Unit;
use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;

class SuccessOk200Test extends Unit
{
    public function testCreateByConstructorWithDefaultArguments(): void
    {
        $requestResult = new SuccessOk200();

        $this->assertIfInstanceOfSuccessOk200($requestResult);
        $this->assertIfImplementRequestResultInterface($requestResult);
        $this->assertResponseResultState($requestResult, 'Ok', 200, false);
    }

    public function testCreateByConstructorWithArguments(): void
    {
        $requestResult = new SuccessOk200("Текст сообщения", 200, false);

        $this->assertIfInstanceOfSuccessOk200($requestResult);
        $this->assertIfImplementRequestResultInterface($requestResult);
        $this->assertResponseResultState($requestResult, 'Текст сообщения', 200, false);
    }

    public function testCreateByFactoryMethodWithDefaultArguments(): void
    {
        $requestResult = SuccessOk200::create();

        $this->assertIfInstanceOfSuccessOk200($requestResult);
        $this->assertIfImplementRequestResultInterface($requestResult);
        $this->assertResponseResultState($requestResult, 'Ok', 200, false);
    }

    public function testCreateByFactoryMethodWithArguments(): void
    {
        $requestResult = SuccessOk200::create("Текст сообщения", 200, false);

        $this->assertIfInstanceOfSuccessOk200($requestResult);
        $this->assertIfImplementRequestResultInterface($requestResult);
        $this->assertResponseResultState($requestResult, 'Текст сообщения', 200, false);
    }

    protected function assertIfInstanceOfSuccessOk200(RequestResultInterface $requestResult): void
    {
        $this->assertInstanceOf(SuccessOk200::class, $requestResult);
    }

    protected function assertResponseResultState(RequestResultInterface $actualRequestResult, string $message, int $statusCode, bool $isError): void
    {
        $this->assertEquals($message, $actualRequestResult->getMessage());
        $this->assertEquals($statusCode, $actualRequestResult->getStatusCode());
        $this->assertEquals($isError, $actualRequestResult->isError());
    }

    protected function assertIfImplementRequestResultInterface(RequestResultInterface $requestResult): void
    {
        $this->assertInstanceOf(RequestResultInterface::class, $requestResult);
    }
}
