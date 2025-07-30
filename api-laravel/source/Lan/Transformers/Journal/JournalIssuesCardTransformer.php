<?php

namespace Lan\Transformers\Journal;

use Lan\Contracts\DTOs\Journal\JournalIssueCardDTOInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;

class JournalIssuesCardTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(JournalIssueCardDTOInterface $dto): array
    {
        return [
            'id' => (string)$dto->getId(),
            'title' => $dto->getTitle(),
            'issueperyear' => $dto->getIssuePerYear(),
            'issn' => $dto->getIssn(),
            'vac' => $dto->isInVac() ? 'Входит в перечень ВАК' : 'нет',
            'edition' => html_entity_decode(strip_tags($dto->getEdition())),
            'description' => html_entity_decode(strip_tags($dto->getDescription())),
            'email' => $dto->getEmail(),
            'publisher' => $dto->getPublisher(),
            'city' => $dto->getCity(),
            'country' => $dto->getCountry(),
            'year' => $dto->getYear(),
            'active' => $dto->isActive(),
            'cover' => $dto->getCoverUrl(),
            'years' => $dto->getYearsWithIssuesDTO()->toMobileScheme(),
        ];
    }
}
