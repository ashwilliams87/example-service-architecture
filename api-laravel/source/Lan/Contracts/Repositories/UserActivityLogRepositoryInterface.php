<?php

namespace Lan\Contracts\Repositories;

use Ebs\Model\Book;
use Ebs\Model\Journal;
use Ebs\Model\Journal_Article;

interface UserActivityLogRepositoryInterface
{
    public function insertSubscriberVisit(): void;

    public function insertReadBookLog(Book $document): void;

    public function insertReadArticleLog(Journal_Article $document): void;

    public function insertFailBookLog(Book $document): void;

    public function insertFailJournalLog(Journal $document): void;
}
