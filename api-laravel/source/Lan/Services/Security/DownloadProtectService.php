<?php

namespace Lan\Services\Security;

use Ebs\Core\Model\Document;
use Lan\Contracts\Services\Security\DownloadProtectorServiceInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\Ebs\Helper\DownloadProtect\DownloadProtectService as LanEbsDownloadProtectorService;

class DownloadProtectService implements DownloadProtectorServiceInterface
{
    private LanEbsDownloadProtectorService $downloadProtectorService;
    public function __construct(
        private SecurityServiceInterface $securityService,
    )
    {
        $this->downloadProtectorService = LanEbsDownloadProtectorService::init();
    }

    private function canDownload($userId, $documentId, string $documentClassName): bool
    {
        return $this->downloadProtectorService->canDownload($userId, $documentId, $documentClassName);
    }

    public function isDownloadPossible(Document $document): bool
    {
        $user = $this->securityService->getUser();

        if (!$user->isActive()) {
            return false;
        }

        if ($document->get('available') == 0) {
            return false;
        }

        if (!$this->canDownload(
            userId: $user->getPkValue(),
            documentId: $document->getPkValue(),
            documentClassName: get_class($document)
        )) {
            return false;
        }

        return true;
    }
}
