<?php

namespace Lan\Helpers;

use Ebs\Core\Model\Document;
use Ice\Helper\Date;
use Ice\Model\User;
use Lan\Enums\DocumentType;

class DocumentHelper
{
    public static function getExpiredDate(Document $document, User $user): string
    {
        $userExpiredAt = $user->get('/expired_at');
        $documentExpiredAt = $document->getRaw('expired_date', Date::get(Date::START, Date::FORMAT_MYSQL_DATE));

        $expiredDate = $userExpiredAt < $documentExpiredAt ? $userExpiredAt : $documentExpiredAt;

        return $expiredDate ?? '';
    }

    public static function checkIfDocumentTypeIsBook(int $documentTypeId): bool
    {
        return $documentTypeId === DocumentType::BOOK->value;
    }

    public static function checkIfDocumentTypeIsJournal(int $documentTypeId): bool
    {
        return $documentTypeId === DocumentType::JOURNAL->value;
    }
}
