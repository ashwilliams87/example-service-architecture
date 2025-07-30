<?php

namespace Lan\DTOs\Book\Responses;

use Lan\Contracts\DataTypes\RequestResult\RequestResultInterface;
use Lan\Contracts\DTOs\DocumentDownloadResponseDTOInterface;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;

class DocumentDownloadResponseDTO implements DocumentDownloadResponseDTOInterface
{
    private function __construct(
        private readonly string                 $filePath,
        private readonly RequestResultInterface $status,
    )
    {

    }

    public static function create(
        string                 $filePath = '',
        RequestResultInterface $status = new SuccessOk200(),

    ): static
    {
        return new self(
            filePath: $filePath,
            status: $status,
        );
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public function getHttpStatusResult(): RequestResultInterface
    {
        return $this->status;
    }
}
