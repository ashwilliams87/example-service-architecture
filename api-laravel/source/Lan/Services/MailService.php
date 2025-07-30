<?php

namespace Lan\Services;

use Ice\Message\Mail;
use Ice\Model\Log_Message;
use Lan\Contracts\Services\MailServiceInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;

class MailService implements MailServiceInterface
{
    public function __construct(
        private SecurityServiceInterface $securityService,
    )
    {

    }

    public function sendCurrentUserDeactivationRequestEmail(): Log_Message
    {
        $jwt = $this->securityService->getJwt();

        $message = 'Пользователь id:' . $this->securityService->getUser()->getPkValue();
        $message .= ' email: ' . $jwt->getUserEmail() . ' ФИО:' . $jwt->getFio() . ' просит удалить его аккаунт.';

        return $this->createMail()
            ->setSubject('Запрос на удаления пользователя')
            ->setBody($message)
            ->setRecipients(['ebs3@lanbook.ru', 'ebs@lanbook.ru'])
            ->send();
    }

    /**
     * Отдельный фабричный метод, чтобы замокать можно было в тестах
     */
    private function createMail(): Mail
    {
        return Mail::create();
    }
}
