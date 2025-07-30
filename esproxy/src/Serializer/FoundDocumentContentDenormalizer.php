<?php

namespace App\Serializer;

use App\DTO\Response\FoundContentDTO;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FoundDocumentContentDenormalizer implements DenormalizerInterface
{
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): FoundContentDTO
    {
        $metadata = [
            'book_id' => $data['book_id'] ?? '',
            'name' => $data['name'] ?? '',
            'authors' => $data['authors'] ?? '',
            'toc_cotent' => $data['toc_content'] ?? '',
            'page_number' => $data['page_number'] ?? 0
        ];

        return new FoundContentDTO(
            id: $data['id'],
            score: $data['score'],
            content: implode(' ', $data['hightlights']['page_content']),
            metadata: $metadata
        );
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === FoundContentDTO::class && is_array($data);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [FoundContentDTO::class => true];
    }
}