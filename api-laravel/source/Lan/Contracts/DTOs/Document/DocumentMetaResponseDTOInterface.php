<?php

namespace Lan\Contracts\DTOs\Document;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\HttpStatusResultable;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;

interface DocumentMetaResponseDTOInterface extends LanDTOInterface, HttpStatusResultable
{
    public static function create(string $meta = '', RequestResultInterface $status = new SuccessOk200()): static;

    public function getMeta(): string;
}
