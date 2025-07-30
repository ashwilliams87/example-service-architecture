<?php
namespace Lan\Contracts\DTOs\Document;

use Lan\Contracts\DTOs\LanDTOInterface;

interface DocumentIdRequestDTOInterface extends LanDTOInterface
{
    public function getId(): int;
}
