<?php

namespace App\Http\Controllers\Ebs;

use App\Http\Controllers\EbsController;
use App\Http\Requests\Book\BookCardRequest;
use App\Http\Requests\Book\BookListRequest;
use App\Http\Requests\Document\DocumentCreateKeyRequest;
use App\Http\Requests\Document\DocumentDownloadRequest;
use App\Http\Requests\Document\DocumentMetaRequest;
use Illuminate\Http\Response;
use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\BookServiceInterface;
use Lan\DataTypes\FileTypes\AudioFileType;
use Lan\DataTypes\FileTypes\EpubFileType;
use Lan\DataTypes\FileTypes\PdfFileType;
use Lan\DataTypes\FileTypes\TextFileType;

class BookController extends EbsController
{
    public function __construct(
        public BookServiceInterface     $bookService,
        public ApiResponseServiceInterface $apiResponseService
    )
    {

    }

    public function bookCard(BookCardRequest $request): Response
    {
        $bookDTO = $this->bookService->getBook($request->toDTO());

        if ($bookDTO->isEmpty()) {
            return $this->apiResponseService->makeNotFoundResponse();
        }

        return $this->apiResponseService->makeSuccessResponseWithObject($bookDTO);
    }

    public function bookList(BookListRequest $request): Response
    {
        $bookListResponseDTO = $this->bookService->getBooksFromCatalog($request->toDTO());

        return $this->apiResponseService->makeSuccessResponseWithArray($bookListResponseDTO);
    }

    public function bookCreateKey(DocumentCreateKeyRequest $request): Response
    {
        $responseDTO = $this->bookService->createBookKey($request->toDTO());

        if ($responseDTO->getHttpStatusResult()->isError()) {
            return $this->apiResponseService->makeErrorResponse($responseDTO->getHttpStatusResult());
        }

        return $this->apiResponseService->makeSuccessResponseWithObject($responseDTO);
    }

    public function bookMeta(DocumentMetaRequest $request): Response
    {
        $responseDTO = $this->bookService->getBookMeta($request->toDTO());

        if ($responseDTO->getHttpStatusResult()->isError()) {
            return $this->apiResponseService->makeErrorResponse($responseDTO->getHttpStatusResult());
        }

        return $this->apiResponseService->makeSuccessResponseWithObject($responseDTO);
    }

    public function downloadPdf(DocumentDownloadRequest $request): ?Response
    {
        return $this->getDocumentFile($request, new PdfFileType());
    }

    public function downloadEpub(DocumentDownloadRequest $request): ?Response
    {
        return $this->getDocumentFile($request, new EpubFileType());
    }

    public function downloadText(DocumentDownloadRequest $request): ?Response
    {
        return $this->getDocumentFile($request, new TextFileType());
    }

    public function downloadAudio(DocumentDownloadRequest $request): ?Response
    {
        return $this->getDocumentFile($request, new AudioFileType());
    }

    private function getDocumentFile(
        DocumentDownloadRequest $request,
        FileTypeInterface       $fileType
    )
    {
        $responseDTO = $this->bookService->getDownloadFilePath($request->toDTO(), $fileType);

        if ($responseDTO->getHttpStatusResult()->isError()) {
            return $this->apiResponseService->makeErrorResponse($responseDTO->getHttpStatusResult());
        }

        $this->apiResponseService->sendEncryptedFileToClient($responseDTO->getFilePath());
    }
}
