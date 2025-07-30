<?php

namespace Lan\Contracts\Services;


use Lan\Contracts\DataTypes\FileTypes\FileTypeInterface;
use Lan\Contracts\DTOs\Document\DocumentCipherKeyResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentMetaResponseDTOInterface;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\DocumentDownloadResponseDTOInterface;
use Lan\Contracts\DTOs\Journal\JournalIssueCardDTOInterface;
use Lan\Contracts\DTOs\MobileListDTOInterface;

interface JournalServiceInterface
{
    public function getJournalsFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): MobileListDTOInterface;

    public function getArticlesFromJournal(DocumentIdRequestDTOInterface $requestDTO): MobileListDTOInterface;

    public function getJournalIssuesCard(DocumentIdRequestDTOInterface $requestDTO): JournalIssueCardDTOInterface;

    public function createArticleKey(DocumentIdRequestDTOInterface $requestDTO): DocumentCipherKeyResponseDTOInterface;

    public function getArticleMeta(DocumentIdRequestDTOInterface $requestDTO): DocumentMetaResponseDTOInterface;

    public function getArticleDownloadFilePath(DocumentIdRequestDTOInterface $requestDTO, FileTypeInterface $fileType): DocumentDownloadResponseDTOInterface;
}
