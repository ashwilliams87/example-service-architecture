<?php

namespace Lan\Transformers\Search;

use Lan\Contracts\DTOs\Book\BookCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class SearchBookCardTransformer implements TransformMobile
{
    public function __construct()
    {

    }

    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(BookCardDTOInterface $dto): array
    {
        return [
            'id' => $dto->getId(),
            'title' => $dto->getTitle(),
            'word' => $dto->getWord(),
            'snippet' => $dto->getSnippet(),
            'hasPdf' => $dto->isHasPdf(),
            'hasEpub' => $dto->isHasEpub(),
            'hasAudio' => $dto->isHasAudio(),
            'hasSyntex' => $dto->isHasSyntex(),
            'book_expired' => !$dto->getBookExpired()->isEmpty() ? $dto->getBookExpired()->toDateString() : null,
            'book_private' => $dto->isBookPrivate() ? 1 : 0,
            'synthesizer_editor' => $dto->getSynthesizerEditor(),
            'synthesizer_quality' => $dto->getSynthesizerQuality(),
            'active' => $dto->isDocumentActive(),
            'author' => $dto->getAuthor(),
            'publisher' => $dto->getPublisher(),
        ];
    }
}
