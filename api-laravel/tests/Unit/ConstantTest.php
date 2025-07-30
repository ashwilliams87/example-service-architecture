<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use Tests\Support\UnitTester;

class ConstantTest extends Unit
{
    protected UnitTester $tester;

    /**
     * Константа объявляется в tests/Unit/_unit_bootstrap.php
     */
    public function testEbsDomainConstant(): void
    {
        $this->assertTrue(defined('EBS_DOMAIN'));
        $this->assertEquals('ebs.test.ru', EBS_DOMAIN);
    }
}
