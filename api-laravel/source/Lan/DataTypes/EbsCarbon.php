<?php

namespace Lan\DataTypes;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\Month;
use Carbon\WeekDay;
use DateTimeInterface;
use DateTimeZone;
use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\DataTypes\EmptyableState\EmptyUtcDate;

class EbsCarbon implements EmptyableInterface
{
    private DateTimeInterface|WeekDay|Month|string|int|float|null $time = 0;
    private DateTimeZone|string|int|null $timezone = 'MSK';

    private CarbonInterface $carbonObject;
    private EmptyableInterface $emptyableDateState;

    private function __construct(
        float|DateTimeInterface|int|string|WeekDay|Month|null $time = null,
        int|DateTimeZone|string|null                          $timezone = null,
        CarbonInterface                                       $carbonObject,
        EmptyableInterface                                    $emptyableDateState = new EmptyUtcDate()
    )
    {
        $this->carbonObject = $carbonObject;
        $this->emptyableDateState = $emptyableDateState;
        $this->time = $time;
        $this->timezone = $timezone;
    }

    public function isEmpty(null $verifiable = null): bool
    {
        return $this->emptyableDateState->isEmpty($this->carbonObject->toDateTimeString());
    }

    public function toDateTimeString(string $unitPrecision = 'second'): string
    {
        return $this->carbonObject->toDateTimeString($unitPrecision);
    }

    public function toDateString(): string
    {
        return $this->carbonObject->toDateString();
    }

    public static function parse(
        DateTimeInterface|WeekDay|Month|string|int|float|null $time,
        DateTimeZone|string|int|null $timezone = null
    ): static
    {
        return new self($time, $timezone, Carbon::parse($time, $timezone));
    }

    /**
     * @param float|DateTimeInterface|int|string|WeekDay|Month|null $time
     * @param int|DateTimeZone|string|null $timezone
     * @return EbsCarbon
     */
    public static function create(
        float|DateTimeInterface|int|string|WeekDay|Month|null $time = null,
        int|DateTimeZone|string|null                          $timezone = null
    ): self
    {
        return new self($time, $timezone, new Carbon(empty($time) ? 0 : $time, $timezone));
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }
}
