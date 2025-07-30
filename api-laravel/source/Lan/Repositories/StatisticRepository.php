<?php

namespace Lan\Repositories;

use Ebs\Action\Log_BookRead;
use Ebs\Action\Log_JournalArticleRead;
use Ebs\Model\Book_Category_Link;
use Ebs\Model\Category;
use Ebs\Model\Journal;
use Ebs\Model\Journal_Article;
use Ebs\Model\Journal_Category_Link;
use Ebs\Model\Journal_Issue;
use Ebs\Model\Packet_Dynamic;
use Ice\Core\Debuger;
use Ice\Helper\Date;
use Ice\Model\User;
use Lan\Contracts\DTOs\Statistic\StatisticItemsDTOInterface;
use Lan\Contracts\Repositories\StatisticRepositoryInterface;
use Lan\Contracts\Repositories\UserRepositoryInterface;
use Lan\Contracts\Services\Security\SecurityServiceInterface;
use Ebs\Model\Book;

class StatisticRepository implements StatisticRepositoryInterface
{
    private string $logCreationTime;
    private User $authenticatedUser;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected SecurityServiceInterface $securityService,
    )
    {
        $this->authenticatedUser = $this->userRepository->getAuthenticatedUser();
    }

    public function logReadStatisticItemsToAuthenticatedUserSubscriber(StatisticItemsDTOInterface $statisticItemsDTO): void
    {

        if(!$this->authenticatedUser->getPkValue()){
            Debuger::dumpToFile('Ошибка записи лога юзера пустой юзер');
            Debuger::dumpToFile($this->authenticatedUser);
            return;
        }

        $userSubscriber = $this->authenticatedUser->getSubscriber();
        if(!$userSubscriber){
            Debuger::dumpToFile('Ошибка записи лога юзера пустой юзер или подписчик');
            Debuger::dumpToFile($this->authenticatedUser);
            Debuger::dumpToFile($userSubscriber);
        }

        $this->logCreationTime = Date::get();
        foreach ($statisticItemsDTO->getStatisticItems() as $deviceStatisticItem){
            if($this->checkIfItemContainsBookStatistic($deviceStatisticItem)){
                $this->logBookStatistic($deviceStatisticItem);
                continue;
            }
            if($this->checkIfItemContainsjournalArticleStatistic($deviceStatisticItem)){
                $this->logJournalArticleStatistic($deviceStatisticItem);
                continue;
            }

            throw new \Exception('Ошибка записи статистики, некорректная сущность');
        }
    }

    private function checkIfItemContainsBookStatistic(mixed $item)
    {
        return $item['entity'] === 'book';
    }

    private function checkIfItemContainsjournalArticleStatistic(mixed $item)
    {
        return $item['entity'] === 'journalArticle';
    }

    private function logBookStatistic(array $deviceStatisticItem): void
    {
        /** @var Book $document */
        $document = Book::createQueryBuilder()
            ->pk((int)$deviceStatisticItem['entity_id'])
            ->getSelectQuery('*', [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getModel();

        /** @var Category $category */
        $category = Category::createQueryBuilder()
            ->inner(Book_Category_Link::class, '/pk', 'Book_Category_Link.category_id=Category.id AND Book_Category_Link.book_id=' . $document->getPkValue())
            ->getSelectQuery(['/pk', 'area_knowledge__fk'], [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getModel();

        $logRead = [
            'create_time' => $this->logCreationTime,
            'read_date' => Date::get($this->logCreationTime, 'Y-m-d'),
            'by_ip' => 0,
            'by_app' => 1,
            'book__fk' => $document->getPkValue(),
            'publisher__fk' => $document->get('publisher__fk'),
            'category__fk' => $category->getPkValue(),
            'subscriber__fk' => $this->authenticatedUser->getSubscriber()->getPkValue(),
            'packet_dynamic__fk' => Packet_Dynamic::create([
                'publisher__fk' => $document->get('publisher__fk'),
                'category__fk' => $category->get('area_knowledge__fk')
            ])->find('/pk')->getPkValue(),
            'session' => $this->securityService ->getSessionId(),
            'page_views' => (int)$deviceStatisticItem['page'],
            'user__fk' => $this->authenticatedUser->getPkValue(),

            'views_inc' => (int)$deviceStatisticItem['page']
        ];

        Log_BookRead::call($logRead, 0, true);
    }

    private function logJournalArticleStatistic(array $deviceStatisticItem): void
    {
        /** @var Journal_Article $document */
        $document = Journal_Article::createQueryBuilder()
            ->inner(Journal_Issue::class, 'journal__fk')
            ->inner(Journal::class, 'publisher__fk')
            ->pk((int)$deviceStatisticItem['entity_id'])
            ->getSelectQuery(['/pk', 'journal_issue__fk'], [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getModel();

        /** @var Category $category */
        $category = Category::createQueryBuilder()
            ->inner(Journal_Category_Link::class, '/pk', 'Journal_Category_Link.category_id=Category.id')
            ->inner(Journal::class)
            ->inner(Journal_Issue::class)
            ->inner(Journal_Article::class, '/pk', 'Journal_Article.journal_issue_id=Journal_Issue.id AND Journal_Article.id=' . $document->getPkValue())
            ->getSelectQuery('/pk', [], 'Ice\DataSource\Mysqli/front.ebs')
            ->getModel();

        $logRead = [
            'create_time' => $this->logCreationTime,
            'read_date' => Date::get($this->logCreationTime, 'Y-m-d'),
            'by_ip' => 0,
            'by_app' => 1,
            'journal_article__fk' => $document->getPkValue(),
            'journal_issue__fk' => $document->get('journal_issue__fk'),
            'journal__fk' => $document->getRaw('journal__fk'),
            'publisher__fk' => $document->getRaw('publisher__fk'),
            'category__fk' => $category->getPkValue(),
            'subscriber__fk' => $this->authenticatedUser->getSubscriber()->getPkValue(),
            'session' => $this->securityService->getSessionId(),
            'page_views' => (int)$deviceStatisticItem['page'],
            'user__fk' => $this->authenticatedUser->getPkValue(),
            'views_inc' => (int)$deviceStatisticItem['page']
        ];

        Log_JournalArticleRead::call($logRead, 0, true);
    }
}
