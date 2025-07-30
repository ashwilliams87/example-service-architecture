<?php

namespace Lan\DTOs\Auth\Requests;


use Lan\Contracts\DTOs\Auth\IpAddressDTOInterface;

class IpRequestDTO implements IpAddressDTOInterface
{
    public function __construct(
        private readonly string $ipAddress,
    )
    {

    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
