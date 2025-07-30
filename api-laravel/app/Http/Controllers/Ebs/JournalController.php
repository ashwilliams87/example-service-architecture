<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Book\ArticleListRequest;
use App\Http\Requests\Document\DocumentCreateKeyRequest;
use App\Http\Requests\Document\DocumentDownloadRequest;
use App\Http\Requests\Document\DocumentMetaRequest;
use App\Http\Requests\Journal\JournalIssueListRequest;
use App\Http\Requests\Journal\JournalListRequest;
use Illuminate\Http\Response;
use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\JournalServiceInterface;
use Lan\DataTypes\FileTypes\AudioFileType;
use Lan\DataTypes\FileTypes\EpubFileType;
use Lan\DataTypes\FileTypes\PdfFileType;
use Lan\DataTypes\FileTypes\TextFileType;

class JournalController extends EbsController
{
    public function __construct(
        public JournalServiceInterface  $journalService,
        public ApiResponseServiceInterface $apiResponseService
    )
    {

    }

    public function journalList(JournalListRequest $request): Response
    {
        return $this->apiResponseService->makeSuccessResponseWithArray(
            $this->journalService->getJournalsFromCatalog($request->toDTO())
        );
    }

    public function articleList(ArticleListRequest $request): Response
    {
        return $this->apiResponseService->makeSuccessResponseWithObject(
            $this->journalService->getArticlesFromJournal($request->toDTO())
        );
    }

    public function journalIssueCard(JournalIssueListRequest $request): Response
    {
        $responseDTO = $this->journalService->getJournalIssuesCard($request->toDTO());

        if ($responseDTO->isEmpty()) {
            return $this->apiResponseService->makeNotFoundResponse();
        }

        return $this->apiResponseService->makeSuccessResponseWithObject($responseDTO);
    }


    public function articleCreateKey(DocumentCreateKeyRequest $request): Response
    {
        $responseDTO = $this->journalService->createArticleKey($request->toDTO());

        if ($responseDTO->getHttpStatusResult()->isError()) {
            return $this->apiResponseService->makeErrorResponse($responseDTO->getHttpStatusResult());
        }

        return $this->apiResponseService->makeSuccessResponseWithObject($responseDTO);
    }

    public function articleMeta(DocumentMetaRequest $request): Response
    {
        $responseDTO = $this->journalService->getArticleMeta($request->toDTO());

        if ($responseDTO->getHttpStatusResult()->isError()) {
            return $this->apiResponseService->makeErrorResponse($responseDTO->getHttpStatusResult());
        }

        return $this->apiResponseService->makeSuccessResponseWithObject($responseDTO);
    }

    public function downloadPdf(DocumentDownloadRequest $request): ?Response
    {
        return $this->downloadFile($request, new PdfFileType());
    }

    public function downloadEpub(DocumentDownloadRequest $request): ?Response
    {
        return $this->downloadFile($request, new EpubFileType());
    }

    public function downloadText(DocumentDownloadRequest $request): ?Response
    {
        return $this->downloadFile($request, new TextFileType());
    }

    public function downloadAudio(DocumentDownloadRequest $request): ?Response
    {
        return $this->downloadFile($request, new AudioFileType());
    }

    private function downloadFile(
        DocumentDownloadRequest $request,
        FileTypeInterface      $fileType
    )
    {
        $responseDTO = $this->journalService->getArticleDownloadFilePath($request->toDTO(), $fileType);

        if ($responseDTO->getHttpStatusResult()->isError()) {
            return $this->apiResponseService->makeErrorResponse($responseDTO->getHttpStatusResult());
        }

        $this->apiResponseService->sendEncryptedFileToClient($responseDTO->getFilePath());
    }
}
