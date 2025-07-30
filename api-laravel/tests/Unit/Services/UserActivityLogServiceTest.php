<?php


namespace Tests\Unit\Services;

use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Ebs\Model\Book;
use Ebs\Model\Book_Data;
use Ebs\Model\Journal;
use Ebs\Model\Journal_Article;
use Ebs\Model\Subscriber;
use Ice\Model\User;
use Lan\Repositories\UserActivityLogRepository;
use Lan\Services\Security\SecurityService;
use Lan\Services\UserActivityLogService;
use Tests\Support\UnitTester;

class UserActivityLogServiceTest extends Unit
{
    protected UnitTester $tester;

    public function testLogSubscriberVisit(): void
    {
        $userActivityLogRepositoryMock = $this->make(UserActivityLogRepository::class, [
            'insertSubscriberVisit' => Expected::once(function (): void {
                // void
            }),
            'insertReadBookLog' => Expected::never(),
            'insertReadArticleLog' => Expected::never(),
            'insertFailBookLog' => Expected::never(),
            'insertFailJournalLog' => Expected::never(),

        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'getSubscriber' => Expected::once(function () {
                return Subscriber::create();
            }),
            'getUser' => Expected::once(function () {
                return User::create();
            }),
        ]);

        $userActivityLogService = new UserActivityLogService(
            securityService: $securityServiceMock,
            userActivityLogRepository: $userActivityLogRepositoryMock,
        );

        $userActivityLogService->logSubscriberVisit();
    }

    public function testLogDocumentFailOnBook(): void
    {
        $userActivityLogRepositoryMock = $this->make(UserActivityLogRepository::class, [
            'insertSubscriberVisit' => Expected::never(),
            'insertReadBookLog' => Expected::never(),
            'insertReadArticleLog' => Expected::never(),
            'insertFailBookLog' => Expected::once(function ($book): void {
                $this->assertEquals($book, Book::create());
            }),
            'insertFailJournalLog' => Expected::never(),

        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'getSubscriber' => Expected::once(function () {
                return Subscriber::create();
            }),
            'getUser' => Expected::never(),
        ]);

        $userActivityLogService = new UserActivityLogService(
            securityService: $securityServiceMock,
            userActivityLogRepository: $userActivityLogRepositoryMock,
        );

        $userActivityLogService->logDocumentFail(Book::create());
    }

    public function testLogDocumentFailOnJournal(): void
    {
        $userActivityLogRepositoryMock = $this->make(UserActivityLogRepository::class, [
            'insertSubscriberVisit' => Expected::never(),
            'insertReadBookLog' => Expected::never(),
            'insertReadArticleLog' => Expected::never(),
            'insertFailBookLog' => Expected::never(),
            'insertFailJournalLog' => Expected::once(function ($journal): void {
                $this->assertEquals($journal, Journal::create());
            }),

        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'getSubscriber' => Expected::once(function () {
                return Subscriber::create();
            }),
            'getUser' => Expected::never(),
        ]);

        $userActivityLogService = new UserActivityLogService(
            securityService: $securityServiceMock,
            userActivityLogRepository: $userActivityLogRepositoryMock,
        );

        $userActivityLogService->logDocumentFail(Journal::create());
    }

    public function testLogDocumentFailOnUnknownType(): void
    {
        $userActivityLogRepositoryMock = $this->make(UserActivityLogRepository::class, [
            'insertSubscriberVisit' => Expected::never(),
            'insertReadBookLog' => Expected::never(),
            'insertReadArticleLog' => Expected::never(),
            'insertFailBookLog' => Expected::never(),
            'insertFailJournalLog' => Expected::never(),

        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'getSubscriber' => Expected::once(function () {
                return Subscriber::create();
            }),
            'getUser' => Expected::never(),
        ]);

        $userActivityLogService = new UserActivityLogService(
            securityService: $securityServiceMock,
            userActivityLogRepository: $userActivityLogRepositoryMock,
        );

        $this->expectException(\InvalidArgumentException::class);
        $userActivityLogService->logDocumentFail(Book_Data::create());
    }

    public function testLogDocumentReadOnBook(): void
    {
        $userActivityLogRepositoryMock = $this->make(UserActivityLogRepository::class, [
            'insertSubscriberVisit' => Expected::never(),
            'insertReadBookLog' => Expected::once(function ($book): void {
                $this->assertEquals($book, Book::create());
            }),
            'insertReadArticleLog' => Expected::never(),
            'insertFailBookLog' => Expected::never(),
            'insertFailJournalLog' => Expected::never(),

        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'getSubscriber' => Expected::once(function () {
                return Subscriber::create();
            }),
            'getUser' => Expected::never(),
        ]);

        $userActivityLogService = new UserActivityLogService(
            securityService: $securityServiceMock,
            userActivityLogRepository: $userActivityLogRepositoryMock,
        );

        $userActivityLogService->logDocumentRead(Book::create());
    }

    public function testLogDocumentReadOnJournalArticle(): void
    {
        $userActivityLogRepositoryMock = $this->make(UserActivityLogRepository::class, [
            'insertSubscriberVisit' => Expected::never(),
            'insertReadBookLog' => Expected::never(),
            'insertReadArticleLog' => Expected::once(function ($journalArticle): void {
                $this->assertEquals($journalArticle, Journal_Article::create());
            }),
            'insertFailBookLog' => Expected::never(),
            'insertFailJournalLog' => Expected::never(),

        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'getSubscriber' => Expected::once(function () {
                return Subscriber::create();
            }),
            'getUser' => Expected::never(),
        ]);

        $userActivityLogService = new UserActivityLogService(
            securityService: $securityServiceMock,
            userActivityLogRepository: $userActivityLogRepositoryMock,
        );

        $userActivityLogService->logDocumentRead(Journal_Article::create());
    }

    public function testLogDocumentReadOnUnknownType(): void
    {
        $userActivityLogRepositoryMock = $this->make(UserActivityLogRepository::class, [
            'insertSubscriberVisit' => Expected::never(),
            'insertReadBookLog' => Expected::never(),
            'insertReadArticleLog' => Expected::never(),
            'insertFailBookLog' => Expected::never(),
            'insertFailJournalLog' => Expected::never(),
        ]);

        $securityServiceMock = $this->make(SecurityService::class, [
            'getSubscriber' => Expected::once(function () {
                return Subscriber::create();
            }),
            'getUser' => Expected::never(),
        ]);

        $userActivityLogService = new UserActivityLogService(
            securityService: $securityServiceMock,
            userActivityLogRepository: $userActivityLogRepositoryMock,
        );

        $this->expectException(\InvalidArgumentException::class);
        $userActivityLogService->logDocumentRead(Book_Data::create());
    }
}
