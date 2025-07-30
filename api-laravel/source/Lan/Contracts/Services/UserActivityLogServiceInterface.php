<?php

namespace Lan\Contracts\Services;

use Ice\Core\Model;

interface UserActivityLogServiceInterface
{
    public function logSubscriberVisit(): void;
    public function logDocumentFail(Model $document): void;
    public function logDocumentRead(Model $document): void;
}
