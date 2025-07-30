<?php

namespace Lan\Contracts\Services;

use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\Publisher\PublisherListResponseDTOInterface;

interface RegistrationServiceInterface
{
    public function getPublishers(DocumentsTypeRequestDTOInterface $requestDTO): PublisherListResponseDTOInterface;
}
