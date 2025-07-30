<?php

namespace App\Service;

use App\Contract\DTO\Request\DocumentContentSearchDTOInterface;
use App\Contract\DTO\Request\DocumentSearchDTOInterface;
use App\Contract\DTO\Response\CollectionDTO;
use App\Contract\Repository\BookSearchRepositoryInterface;
use App\Contract\Service\BookSearchServiceInterface;
use App\DTO\Response\FoundContentDTO;
use App\DTO\Response\FoundDocumentCollectionDTO;
use App\DTO\Response\FoundDocumentDTO;
use App\Serializer\FoundDocumentContentDenormalizer;
use App\Serializer\FoundDocumentDenormalizer;

class BookSearchService implements BookSearchServiceInterface
{
    public function __construct(
        private readonly BookSearchRepositoryInterface    $bookSearchRepository,
        private readonly FoundDocumentDenormalizer        $foundDocumentSerializer,
        private readonly FoundDocumentContentDenormalizer $foundDocumentContentSerializer

    )
    {

    }

    public function searchBookCollection(DocumentSearchDTOInterface $documentSearchDTO): CollectionDto
    {
        $searchResultSet = $this->bookSearchRepository->searchBooks($documentSearchDTO);
        $collection = new FoundDocumentCollectionDTO();

        foreach ($searchResultSet as $searchResult) {
            $document = $searchResult->getData();
            $dto = $this->foundDocumentSerializer->denormalize($document, FoundDocumentDTO::class);
            $collection->addDocument($dto);
        }

        return $collection;
    }

    public function searchBookContentCollection(DocumentContentSearchDTOInterface $documentContentSearchDTO): CollectionDTO
    {
        $searchResultSet = $this->bookSearchRepository->searchBookContent($documentContentSearchDTO);

        $collection = new FoundDocumentCollectionDTO();

        foreach ($searchResultSet as $searchResult) {
            $document = $searchResult->getData();
            $document['id'] = $searchResult->getId();
            $document['score'] = $searchResult->getScore();
            $document['hightlights'] = $searchResult->getHighlights();
            $dto = $this->foundDocumentContentSerializer->denormalize($document, FoundContentDTO::class);
            $collection->addDocument($dto);
        }

        return $collection;
    }
}