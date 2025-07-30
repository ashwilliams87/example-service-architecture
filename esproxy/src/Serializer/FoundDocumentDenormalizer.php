<?php

namespace App\Serializer;

use App\DTO\Response\FoundDocumentDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FoundDocumentDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): FoundDocumentDTO
    {
        return new FoundDocumentDTO(
            id: $data['book_id'],
            title: $data['name'] ?? '',
            year: $data['year'] ?? '',
            authors: $data['authors'] ?? '',
        );
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === FoundDocumentDTO::class && is_array($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [FoundDocumentDTO::class => true];
    }
}