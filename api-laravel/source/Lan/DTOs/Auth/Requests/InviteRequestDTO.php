<?php

namespace Lan\DTOs\Auth\Requests;

use Lan\Contracts\DTOs\Auth\InviteRequestDTOInterface;

class InviteRequestDTO implements InviteRequestDTOInterface
{
    public function __construct(
        private readonly string $inviteCode,
    )
    {

    }

    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }

    public static function create(string $inviteCode): static
    {
        return new static($inviteCode);
    }

    public function isValid(): bool
    {
        throw new \Exception('Not implemented');
    }
}
