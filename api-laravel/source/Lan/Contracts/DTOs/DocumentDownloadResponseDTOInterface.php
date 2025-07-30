<?php

namespace Lan\Contracts\DTOs;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;

interface DocumentDownloadResponseDTOInterface extends LanDTOInterface, HttpStatusResultable
{
    public static function create(string $filePath = '', RequestResultInterface $status = new SuccessOk200()): static;

    public function getFilePath(): string;
}
