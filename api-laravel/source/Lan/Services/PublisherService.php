<?php

namespace Lan\Services;

use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\Repositories\PublisherRepositoryInterface;
use Lan\Contracts\Services\PublisherServiceInterface;
use Lan\DTOs\Publisher\Responses\PublisherListResponseResponseDTO;

class PublisherService implements PublisherServiceInterface
{
    public function __construct(
        private PublisherRepositoryInterface $publisherRepository,
    )
    {

    }

    public function getPublishers(DocumentsTypeRequestDTOInterface $requestDTO): PublisherListResponseResponseDTO
    {
        if ($requestDTO->getCategoryId() === 0) {
            $queryResult = $this->publisherRepository->getPrivatePublisherList($requestDTO);
        } else {
            $queryResult = $this->publisherRepository->getPublisherList($requestDTO);
        }

        return PublisherListResponseResponseDTO::createFromIceQueryResultRows($queryResult);
    }
}
