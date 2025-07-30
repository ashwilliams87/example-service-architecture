<?php

namespace Lan\Contracts\Repositories;

use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use stdClass;

interface CategoryRepositoryInterface
{
    /**
     * @return stdClass[]
     */
    public function getBookCategories(): array;

    public function getJournalCategories(): QueryResult;

    /**
     * @return stdClass[]
     */
    public function getAllCategories(): array;

    public function getBookSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;

    public function getJournalSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;

    public function getAllSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;

    public function getPrivateSubCategories(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;
}
