<?php

namespace Lan\DTOs\Auth\Responses;

use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\Contracts\DTOs\User\UserAuthCardInterface;
use Lan\DataTypes\EbsCarbon;
use Lan\DataTypes\EmptyableState\EmptyDTOId;

class UserAuthCardDTO implements UserAuthCardInterface, EmptyableInterface
{
    private function __construct(
        private readonly int                $id,
        private readonly string             $xAuthToken,
        private readonly string             $name,
        private readonly string             $email,
        private readonly string             $subscriber,
        private readonly EbsCarbon          $subscriptionEndDate,
        private readonly EmptyableInterface $emptyableIdState = new EmptyDTOId(),
    )
    {

    }

    public static function create(
        int                $id = 0,
        string             $xAuthToken = '',
        string             $name = '',
        string             $email = '',
        string             $subscriber = '',
        EbsCarbon          $subscriptionEndDate = null,
        EmptyableInterface $emptyableIdState = new EmptyDTOId()
    ): static
    {
        $subscriptionEndDate = $subscriptionEndDate ?? EbsCarbon::create();

        return new self(
            id: $id,
            xAuthToken: $xAuthToken,
            name: $name,
            email: $email,
            subscriber: $subscriber,
            subscriptionEndDate: $subscriptionEndDate,
            emptyableIdState: $emptyableIdState
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getXAuthToken(): string
    {
        return $this->xAuthToken;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSubscriber(): string
    {
        return $this->subscriber;
    }

    public function getSubscriptionEndDate(): EbsCarbon
    {
        return $this->subscriptionEndDate;
    }

    public function isEmpty(null $verifiable = null): bool
    {
        return $this->emptyableIdState->isEmpty($this->getId());
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
