<?php

namespace Lan\Contracts\Repositories;

use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

interface PublisherRepositoryInterface
{
    /**
     * @param DocumentsTypeRequestDTOInterface $requestDTO
     * @return QueryResult
     */
    public function getPublisherList(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;


    /**
     * @param DocumentsTypeRequestDTOInterface $requestDTO
     * @return QueryResult
     */
    public function getPrivatePublisherList(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;
}
