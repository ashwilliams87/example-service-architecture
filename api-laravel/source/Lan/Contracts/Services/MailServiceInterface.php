<?php

namespace Lan\Contracts\Services;

use Ice\Model\Log_Message;

interface MailServiceInterface
{
    public function sendCurrentUserDeactivationRequestEmail(): Log_Message;
}
