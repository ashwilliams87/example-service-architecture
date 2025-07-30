<?php

namespace Lan\Contracts\DTOs\User;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\DataTypes\EbsCarbon;

interface UserAuthCardInterface extends LanDTOInterface
{
    public function getId(): int;

    public function getXAuthToken(): string;

    public function getName(): string;

    public function getEmail(): string;

    public function getSubscriber(): string;

    public function getSubscriptionEndDate(): EbsCarbon;

    public function isEmpty(null $verifiable = null): bool;
}
