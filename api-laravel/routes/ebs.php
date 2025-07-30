<?php

use App\Http\Controllers\Ebs\UserAuthController;
use App\Http\Controllers\Ebs\AuthorController;
use App\Http\Controllers\Ebs\BookController;
use App\Http\Controllers\Ebs\CategoryController;
use App\Http\Controllers\Ebs\JournalController;
use App\Http\Controllers\Ebs\PublisherController;
use App\Http\Controllers\Ebs\SearchController;
use App\Http\Controllers\Ebs\StatisticController;
use App\Http\Controllers\Ebs\SyncController;
use App\Http\Middleware\CopyTokenFromHeaderToCookie;
use App\Http\Middleware\EnsureUserAuthenticated;
use App\Http\Middleware\AfterLogSubscriberVisit;
use App\Http\Middleware\BeforeLogSubscriberVisit;
use Illuminate\Support\Facades\Route;

Route::prefix('1.1')
    ->middleware([
        CopyTokenFromHeaderToCookie::class,
        EnsureUserAuthenticated::class,
    ])
    ->group(function (): void {
        Route::get('/books', [BookController::class, 'bookList'])->middleware(BeforeLogSubscriberVisit::class);
        Route::prefix('/book')
            ->group(function (): void {
                Route::get('/', [BookController::class, 'bookCard'])->middleware(BeforeLogSubscriberVisit::class);

                Route::get('/key', [BookController::class, 'bookCreateKey']);
                Route::get('/meta', [BookController::class, 'bookMeta']);
                Route::get('/pdf', [BookController::class, 'downloadPdf']);
                Route::get('/epub', [BookController::class, 'downloadEpub']);
                Route::get('/text', [BookController::class, 'downloadText']);
                Route::get('/audio', [BookController::class, 'downloadAudio']);
            });

        Route::prefix('/article')
            ->group(function (): void {
                Route::get('/key', [JournalController::class, 'articleCreateKey']);
                Route::get('/meta', [JournalController::class, 'articleMeta']);
                Route::get('/pdf', [JournalController::class, 'downloadPdf']);
                Route::get('/epub', [JournalController::class, 'downloadEpub']);
                Route::get('/text', [JournalController::class, 'downloadText']);
                Route::get('/audio', [JournalController::class, 'downloadAudio']);
            });

        Route::get('/journals', [JournalController::class, 'journalList'])->middleware(BeforeLogSubscriberVisit::class);
        Route::get('/articles', [JournalController::class, 'articleList'])->middleware(BeforeLogSubscriberVisit::class);
        Route::get('/issue', [JournalController::class, 'journalIssueCard'])->middleware(BeforeLogSubscriberVisit::class);

        Route::get('/authors', [AuthorController::class, 'authorCharacterMap'])->middleware(BeforeLogSubscriberVisit::class);
        Route::get('/publishers', [PublisherController::class, 'publisherList'])->middleware(BeforeLogSubscriberVisit::class);
        Route::get('/categories', [CategoryController::class, 'categoryList'])->middleware(BeforeLogSubscriberVisit::class);
        Route::get('/subcategories', [CategoryController::class, 'subCategoryList'])->middleware(BeforeLogSubscriberVisit::class);

        Route::post('/sync/all', [SyncController::class, 'syncAll'])->middleware(BeforeLogSubscriberVisit::class);
        Route::post('/stat/read', [StatisticController::class, 'logReadStatistic'])->middleware(BeforeLogSubscriberVisit::class);

        Route::get('/search', [SearchController::class, 'searchAll']);
        Route::get('/search/books', [SearchController::class, 'searchByDocumentType'])->middleware(BeforeLogSubscriberVisit::class);
        Route::get('/search/journals', [SearchController::class, 'searchByDocumentType'])->middleware(BeforeLogSubscriberVisit::class);
        Route::get('/search/articles', [SearchController::class, 'searchByDocumentType'])->middleware(BeforeLogSubscriberVisit::class);

        Route::prefix('users')
            ->withoutMiddleware(EnsureUserAuthenticated::class)
            ->group(function (): void {
                Route::post('current', [UserAuthController::class, 'logIn'])->middleware(AfterLogSubscriberVisit::class);
                Route::post('current/social', [UserAuthController::class, 'logInBySocial'])->middleware(AfterLogSubscriberVisit::class);

                Route::delete('current', [UserAuthController::class, 'logOut']);
                Route::delete('current/social', [UserAuthController::class, 'logOut']);

                Route::post('register', [UserAuthController::class, 'registerByEmail']);
                Route::post('register/social', [UserAuthController::class, 'registerBySocial'])->middleware(AfterLogSubscriberVisit::class);

                Route::put('current/social/delete', [UserAuthController::class, 'deactivateUser'])->middleware(EnsureUserAuthenticated::class);

                Route::get('check_ip', [UserAuthController::class, 'checkIp']);
                Route::post('recovery', [UserAuthController::class, 'recoverPassword']);
                Route::get('check_invite', [UserAuthController::class, 'checkInvite']);
            });
    });

