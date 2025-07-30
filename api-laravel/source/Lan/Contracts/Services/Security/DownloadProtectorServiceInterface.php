<?php

namespace Lan\Contracts\Services\Security;

use Ebs\Core\Model\Document;

interface DownloadProtectorServiceInterface
{
    public function isDownloadPossible(Document $document): bool;
}
