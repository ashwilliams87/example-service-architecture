<?php

namespace Lan\Contracts\DTOs;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;

interface HttpStatusResultable
{
    public function getHttpStatusResult(): RequestResultInterface;
}
