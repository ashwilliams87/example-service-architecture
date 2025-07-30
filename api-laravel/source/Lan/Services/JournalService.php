<?php

namespace Lan\Services;

use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\Contracts\DTOs\Document\DocumentCipherKeyResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentMetaResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\DocumentDownloadResponseDTOInterface;
use Lan\Contracts\DTOs\Journal\JournalIssueCardDTOInterface;
use Lan\Contracts\DTOs\MobileListDTOInterface;
use Lan\Contracts\Repositories\JournalRepositoryInterface;
use Lan\Contracts\Services\JournalServiceInterface;
use Lan\Contracts\Services\Security\DocumentCryptServiceInterface;
use Lan\Contracts\Services\Security\DownloadProtectorServiceInterface;
use Lan\Contracts\Services\UserActivityLogServiceInterface;
use Lan\DataTypes\RequestResult\Error\NoAccessToResourceError;
use Lan\DataTypes\RequestResult\Error\NotAvailableForDownloadError;
use Lan\DataTypes\RequestResult\Error\ResourceNotFoundError;
use Lan\DataTypes\RequestResult\Success\SuccessOk200;
use Lan\DTOs\Book\Responses\DocumentDownloadResponseDTO;
use Lan\DTOs\Document\DocumentCipherKeyResponseDTO;
use Lan\DTOs\Document\DocumentMetaResponseDTO;
use Lan\DTOs\Journal\Responses\ArticleListResponseDTO;
use Lan\DTOs\Journal\Responses\JournalIssueList\JournalIssuesCardDTO;
use Lan\DTOs\Journal\Responses\JournalListResponseDTO;

class JournalService implements JournalServiceInterface
{
    public function __construct(
        private JournalRepositoryInterface        $journalRepository,
        private DownloadProtectorServiceInterface $downloadProtectorService,
        private DocumentCryptServiceInterface     $documentCryptService,
        private UserActivityLogServiceInterface   $userActivityLogService,
    )
    {

    }

    public function getJournalsFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface
    {
        $repositoryResults = $this->journalRepository->getJournalsWithCoverLinksFromCatalog($requestDTO);
        return JournalListResponseDTO::createFromArrayList($repositoryResults['journals'])
            ->setCount($repositoryResults['count']);
    }

    public function getArticlesFromJournal(DocumentIdRequestDTOInterface $requestDTO): MobileListDTOInterface
    {
        return ArticleListResponseDTO::createFromArrayList($this->journalRepository->getArticlesFromJournal($requestDTO));
    }

    public function getJournalIssuesCard(DocumentIdRequestDTOInterface $requestDTO): JournalIssueCardDTOInterface
    {
        $journalWithIssues = $this->journalRepository->getJournalWithIssuesWithCover($requestDTO->getId());
        if (!$journalWithIssues) {
            return JournalIssuesCardDTO::create();
        }

        if (!$journalWithIssues['active']) {
            $journal = $this->journalRepository->getJournal($requestDTO->getId());
            $this->userActivityLogService->logDocumentFail($journal);
        }

        return JournalIssuesCardDTO::createFromArray($journalWithIssues);
    }

    public function createArticleKey(DocumentIdRequestDTOInterface $requestDTO): DocumentCipherKeyResponseDTOInterface
    {
        $book = $this->journalRepository->getArticle($requestDTO->getId());

        if (!$book->getPk()) {
            return DocumentCipherKeyResponseDTO::create(status: ResourceNotFoundError::create());
        }

        if (!$book->get('available')) {
            return DocumentCipherKeyResponseDTO::create(status: NoAccessToResourceError::create());
        }

        if (!$this->downloadProtectorService->isDownloadPossible($book)) {
            return DocumentCipherKeyResponseDTO::create(status: NotAvailableForDownloadError::create());
        }

        return DocumentCipherKeyResponseDTO::create(
            key: $this->documentCryptService->createKey($book),
            status: SuccessOk200::create()
        );
    }

    public function getArticleMeta(DocumentIdRequestDTOInterface $requestDTO): DocumentMetaResponseDTOInterface
    {
        $article = $this->journalRepository->getArticle($requestDTO->getId());

        if (!$article->getPk()) {
            return DocumentMetaResponseDTO::create(status: ResourceNotFoundError::create());
        }

        $this->userActivityLogService->logDocumentRead($article);

        if (!$this->downloadProtectorService->isDownloadPossible($article)) {
            return DocumentMetaResponseDTO::create(status: NotAvailableForDownloadError::create());
        }

        return DocumentMetaResponseDTO::create(
            meta: $this->documentCryptService->getMeta($article),
            status: SuccessOk200::create()
        );
    }

    public function getArticleDownloadFilePath(
        DocumentIdRequestDTOInterface $requestDTO,
        FileTypeInterface             $fileType,
    ): DocumentDownloadResponseDTOInterface
    {
        $article = $this->journalRepository->getArticle($requestDTO->getId());

        // В логике api-phalcon не было проверки на Not found. Пока не стал реализовывать, но имеет смысл в будущем реализовать
//        if(!$article->getPk()){
//            return BookMetaResponseDTO::create(status: new ResourceNotFoundError());
//        }

        $this->userActivityLogService->logDocumentRead($article);

        if (!$this->downloadProtectorService->isDownloadPossible($article)) {
            return DocumentDownloadResponseDTO::create(status: NotAvailableForDownloadError::create());
        }

        return DocumentDownloadResponseDTO::create(
            filePath: $this->documentCryptService->getEncryptedFilePath($article, $fileType),
            status: SuccessOk200::create()
        );
    }
}
