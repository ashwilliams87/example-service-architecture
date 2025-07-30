<?php

namespace Lan\Contracts\Services\Security;

interface CryptServiceInterface
{
    public function encrypt(
        string $stringToCrypt,
        string $key,
        string $iv,
        bool $is_base = true
    ): string;

    public function decrypt(
        string $encryptedString,
        string $key,
        string $iv,
    ): string;
}
