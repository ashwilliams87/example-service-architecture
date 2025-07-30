<?php

namespace Lan\DTOs\Auth\Responses;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\Auth\SubscriberNameResultDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Auth\SubscriberNameResultTransformer;

class SubscriberNameResultDTO implements SubscriberNameResultDTOInterface
{
    private function __construct(
        private readonly RequestResultInterface $requestResult,
        private readonly string                 $subscriberName,
    )
    {

    }

    public static function create(
        RequestResultInterface $requestResult,
        string                 $subscriberName = ''
    ): self
    {
        return new self(
            requestResult: $requestResult,
            subscriberName: $subscriberName,
        );
    }

    public function getSubscriberName(): string
    {
        return $this->subscriberName;
    }

    public function isValid(): bool
    {
        return !empty($this->subscriberName);
    }

    public function getHttpStatusResult(): RequestResultInterface
    {
        return $this->requestResult;
    }

    public function toMobileScheme(TransformMobile $transformer = new SubscriberNameResultTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
