<?php

namespace Lan\Contracts\Services;

use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\Contracts\DTOs\Book\BookCardDTOInterface;
use Lan\Contracts\DTOs\Book\BookCardListResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentCipherKeyResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentMetaResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\DocumentDownloadResponseDTOInterface;


interface BookServiceInterface
{
    public function getBooksFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): BookCardListResponseDTOInterface;

    public function getBook(DocumentIdRequestDTOInterface $requestDTO): BookCardDTOInterface;

    public function createBookKey(DocumentIdRequestDTOInterface $bookRequestDTO): DocumentCipherKeyResponseDTOInterface;

    public function getBookMeta(DocumentIdRequestDTOInterface $requestDTO): DocumentMetaResponseDTOInterface;

    public function getDownloadFilePath(DocumentIdRequestDTOInterface $requestDTO, FileTypeInterface $fileType): DocumentDownloadResponseDTOInterface;
}
