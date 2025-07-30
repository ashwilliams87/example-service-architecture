<?php
namespace Lan\Transformers\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Journal\Responses\JournalIssueList\YearWithIssuesDTO;

class YearWithIssueTransformer implements TransformMobile
{
    public function transformToMobileScheme(LanDTOInterface $dtoList): array
    {
        return $this->convertToArray($dtoList);
    }

    private function convertToArray(YearWithIssuesDTO $dto): array
    {
        return [
            'name' => $dto->getYear(),
            'issues' => $dto->getIssueList()->toMobileScheme(),
        ];
    }
}
