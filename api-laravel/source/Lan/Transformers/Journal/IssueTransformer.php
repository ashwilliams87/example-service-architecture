<?php
namespace Lan\Transformers\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Journal\Responses\JournalIssueList\IssueDTO;

class IssueTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(IssueDTO $dto): array
    {
        return [
            'id' => $dto->getId(),
            'title' => $dto->getTitle(),
        ];
    }
}
