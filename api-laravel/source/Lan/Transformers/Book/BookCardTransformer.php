<?php

namespace Lan\Transformers\Book;

use Lan\Contracts\DTOs\Book\BookCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class BookCardTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dto): array
    {
        return $this->convertToArray($dto);
    }

    private function convertToArray(BookCardDTOInterface $dto): array
    {

        return [
            'id' => $dto->getId(),
            'title' => $dto->getTitle(),
            'description' => $dto->getDescription(),
            'isbn' => $dto->getIsbn(),
            'edition' => $dto->getEdition(),
            'pages' => (string)$dto->getPages(),
            'book_private' => (int)$dto->isBookPrivate(),
            'publisher__fk' => $dto->getPublisherId(),
            'synthesizer_quality' => $dto->getSynthesizerQuality(),
            'author' => $dto->getAuthor(),
            'year' => $dto->getYear(),
            'active' => $dto->isDocumentActive(),
            'hasPdf' => $dto->isHasPdf(),
            'hasEpub' => $dto->isHasEpub(),
            'hasSyntex' => $dto->isHasSyntex(),
            'hasAudio' => $dto->isHasAudio(),
            'publisher' => $dto->getPublisher(),
            'expired' => !$dto->getExpired()->isEmpty() ? $dto->getExpired()->toDateString() : null,
            'synthesizer_editor' => $dto->getSynthesizerEditor(),
            //дата доступа
            'access_date' => !$dto->getAccessDate()->isEmpty() ? $dto->getExpired()->toDateString() : null,
            //протушкая книжка
            'expired_date' => !$dto->getExpiredDate()->isEmpty() ? $dto->getExpiredDate()->toDateString() : null,
            'cover' => EBS_DOMAIN . '/img/cover/book/' . $dto->getId() . '.jpg',
            'audio' => false,
            'size' => 0,
        ];
    }
}
