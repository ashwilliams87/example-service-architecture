<?php

namespace App\Contract\Service;

interface SecurityServiceInterface
{
    public function isAuth(): bool;

    public function isReader(): bool;

    public function getSubscriberId(): int;

    public function getUser();
}