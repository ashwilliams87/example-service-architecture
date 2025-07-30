<?php

namespace Lan\Services;

use Ebs\Model\Book;
use Ebs\Model\Journal;
use Ebs\Model\Journal_Article;
use Ice\Core\Model;
use Lan\Contracts\Repositories\UserActivityLogRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Lan\Contracts\Services\UserActivityLogServiceInterface;

class UserActivityLogService implements UserActivityLogServiceInterface
{
    public function __construct(
        private SecurityServiceInterface           $securityService,
        private UserActivityLogRepositoryInterface $userActivityLogRepository,
    )
    {

    }

    public function logSubscriberVisit(): void
    {
        if (!$this->securityService->getUser() || !$this->securityService->getSubscriber()) {
            return;
        }

        $this->userActivityLogRepository->insertSubscriberVisit();
    }

    public function logDocumentRead(Model $document): void
    {
        if (!$this->securityService->getSubscriber()) {
            return;
        }

        match (get_class($document)){
            Book::class => $this->userActivityLogRepository->insertReadBookLog($document),
            Journal_Article::class => $this->userActivityLogRepository->insertReadArticleLog($document),
            default => throw new \InvalidArgumentException('Unsupported document type'),
        };
    }

    public function logDocumentFail(Model $document): void
    {
        if (!$this->securityService->getSubscriber()) {
            return;
        }

        match (get_class($document)){
            Book::class => $this->userActivityLogRepository->insertFailBookLog($document),
            Journal::class => $this->userActivityLogRepository->insertFailJournalLog($document),
            default => throw new \InvalidArgumentException('Unsupported document type'),
        };
    }
}
