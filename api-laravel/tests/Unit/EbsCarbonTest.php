<?php


namespace Tests\Unit;

use Codeception\Test\Unit;
use Lan\DataTypes\EbsCarbon;
use Tests\Support\UnitTester;

class EbsCarbonTest extends Unit
{

    protected UnitTester $tester;

    protected function _before(): void
    {
    }

    public function testToDateTimeString(): void
    {
        $time = '2022-12-01 00:00:00';
        $timezone = 'UTC';

        $ebsCarbon = EbsCarbon::parse($time, $timezone);

        $this->assertEquals($time, $ebsCarbon->toDateTimeString());
    }

    public function testToDateString(): void
    {
        $time = '2022-12-01 00:00:00';
        $timezone = 'UTC';

        $ebsCarbon = EbsCarbon::parse($time, $timezone);

        $this->assertEquals('2022-12-01', $ebsCarbon->toDateString());
    }

    public function testParseDate(): void
    {
        $time = '2022-12-01 00:00:00';
        $timezone = 'UTC';

        $ebsCarbon = EbsCarbon::parse($time, $timezone);

        $this->assertInstanceOf(EbsCarbon::class, $ebsCarbon);
        $this->assertEquals($time, $ebsCarbon->toDateTimeString());
        $this->assertEquals($timezone, $ebsCarbon->getTimezone());
    }
}
