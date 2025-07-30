<?php

namespace Lan\Repositories;

use Ebs\Action\Log_BookFail;
use Ebs\Action\Log_BookRead;
use Ebs\Action\Log_JournalArticleRead;
use Ebs\Action\Log_JournalFail;
use Ebs\Action\Log_SubscriberVisit;
use Ebs\Model\Book;
use Ebs\Model\Category;
use Ebs\Model\Journal as EbsJournal;
use Ebs\Model\Journal_Article;
use Ebs\Model\Journal_Category_Link;
use Ebs\Model\Journal_Issue;
use Ebs\Model\Packet_Dynamic;
use Ice\Helper\Date;
use Lan\Contracts\Repositories\UserActivityLogRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;

class UserActivityLogRepository implements UserActivityLogRepositoryInterface
{
    public function __construct(
        private SecurityServiceInterface $securityService,
    )
    {

    }

    public function insertSubscriberVisit(): void
    {
        $subscriberVisit = [
            'subscriber_visit_pk' => md5($this->securityService->getSessionId() . 'adm4dgls' . $this->securityService->getUser()->getPkValue() . Date::get(null, 'dmY')) . Date::get(null, 'dmY'),
            'read_date' => Date::get(null, 'Y-m-d'),
            'user__fk' => $this->securityService->getUser()->getPkValue(),
            'unique_user_id' => $this->securityService->getUniqueUser(),
            'views' => 1,
            'by_lk' => 0,
            'by_ip' => 0,
            'by_app' => 1,
            'by_moodle' => 0,
            'time' => Date::get(Date::START, Date::FORMAT_MYSQL),
            'user_agent' => empty(getallheaders()['User-Agent']) ? 'mobile' : getallheaders()['User-Agent']
        ];

        Log_SubscriberVisit::call($subscriberVisit, 0, true);
    }

    public function insertReadBookLog(Book $document): void
    {
        $packetDynamic = Book::createQueryBuilder()
            ->inner(Category::class, ['/pk'])
            ->inner(Packet_Dynamic::class, '/pk', 'Packet_Dynamic.category_id=Category.area_knowledge_id AND Packet_Dynamic.publisher_id=Book.publisher_id')
            ->pk($document->getPkValue())
            ->getSelectQuery(null)
            ->getRow();

        if ($packetDynamic) {
            $time = Date::get();

            $logRead = [
                'create_time' => $time,
                'read_date' => Date::get($time, 'Y-m-d'),
                'by_ip' => 0,
                'by_app' => 1,
                'book__fk' => $document->getPkValue(),
                'publisher__fk' => $document->get('publisher__fk'),
                'category__fk' => $packetDynamic['category_pk'],
                'subscriber__fk' => $this->securityService->getSubscriber()->getPkValue(),
                'packet_dynamic__fk' => $packetDynamic['packet_dynamic_pk'],
                'session' => $this->securityService->getSessionId(),
                'page_views' => 0,
                'user__fk' => $this->securityService->getUser()->getPkValue(),
            ];

            Log_BookRead::call($logRead, 0, true);
        }
    }

    public function insertReadArticleLog(Journal_Article $document): void
    {
        /** @var Category $category */
        $category = Category::createQueryBuilder()
            ->inner(Journal_Category_Link::class, '/pk', 'Journal_Category_Link.category_id=Category.id')
            ->inner(EbsJournal::class)
            ->inner(Journal_Issue::class)
            ->inner(Journal_Article::class, '/pk', 'Journal_Article.journal_issue_id=Journal_Issue.id AND Journal_Article.id=' . $document->getPkValue())
            ->getSelectQuery('/pk', [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getModel();

        $time = Date::get();

        $logRead = [
            'create_time' => $time,
            'read_date' => Date::get($time, 'Y-m-d'),
            'by_ip' => 0,
            'by_app' => 1,
            'journal_article__fk' => $document->getPkValue(),
            'journal_issue__fk' => $document->get('journal_issue__fk'),
            'journal__fk' => $document->getRaw('journal__fk'),
            'publisher__fk' => $document->getRaw('publisher__fk'),
            'category__fk' => $category->getPkValue(),
            'subscriber__fk' => $this->securityService->getSubscriber()->getPkValue(),
            'session' => $this->securityService->getSessionId(),
            'page_views' => 0,
            'user__fk' => $this->securityService->getUser()->getPkValue(),
        ];

        Log_JournalArticleRead::call($logRead, 0, true);
    }

    public function insertFailBookLog(Book $document): void
    {
        $packetDynamicQuery = Book::createQueryBuilder()
            ->inner(Category::class)
            ->inner(Packet_Dynamic::class, '/pk', 'Packet_Dynamic.category_id=Category.area_knowledge_id AND Packet_Dynamic.publisher_id=Book.publisher_id')
            ->pk($document->getPkValue())
            ->getSelectQuery(null);

        if ($packetDynamicKey = $packetDynamicQuery->getValue()) {
            $time = Date::get();

            $logFail = [
                'create_time' => $time,
                'read_date' => Date::get($time, 'Y-m-d'),
                'book__fk' => $document->getPkValue(),
                'packet_dynamic__fk' => $packetDynamicKey,
                'subscriber__fk' => $this->securityService->getSubscriber()->getPkValue(),
                'user__fk' => $this->securityService->getUser()->getPkValue(),
                'session' => $this->securityService->getSessionId(),
            ];

            Log_BookFail::call($logFail, 0, true);
        }
    }

    public function insertFailJournalLog(EbsJournal $document): void
    {
            $time = Date::get();

            $logFail = [
                'create_time' => $time,
                'subscriber__fk' => $this->securityService->getSubscriber()->getPkValue(),
                'journal__fk' => $document->getPkValue(),
                'session' => $this->securityService->getSessionId(),
                'read_date' => Date::get($time, 'Y-m-d'),
                'user__fk' => $this->securityService->getUser()->getPkValue(),
            ];

            Log_JournalFail::call($logFail, 0, true);
    }
}
