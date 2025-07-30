<?php

namespace Lan\DTOs\Journal\Responses\JournalIssueList;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Journal\YearWithIssueTransformer;

class YearWithIssuesDTO implements LanDTOInterface, Mobile, CreatableFromIceQueryResultRow
{
    private function __construct(
        private readonly int $year,
        private readonly IssueListDTO $issueList,
    )
    {

    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getIssueList(): IssueListDTO
    {
        return $this->issueList;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public static function createFromIceQueryResultRow(array $row): static
    {
        return new static(
            year: $row['name'],
            issueList: IssueListDTO::createFromArrayList($row['issues'])
        );
    }

    public function toMobileScheme(TransformMobile $transformer = new YearWithIssueTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
