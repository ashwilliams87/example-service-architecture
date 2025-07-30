<?php

namespace Lan\Contracts\Repositories;

use Ebs\Model\Book;
use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

interface BookRepositoryInterface
{
    public function getBooksFromCatalog(DocumentsTypeRequestDTOInterface $requestDTO): QueryResult;

    public function getBook(int $bookId): Book;
}
