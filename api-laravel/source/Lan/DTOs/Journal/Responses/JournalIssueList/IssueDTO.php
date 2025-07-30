<?php

namespace Lan\DTOs\Journal\Responses\JournalIssueList;

use Lan\Contracts\DTOs\CreatableFromArray;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\Transformers\Journal\IssueTransformer;

class IssueDTO implements LanDTOInterface, Mobile, CreatableFromArray
{
    private function __construct(
        private readonly int $id,
        private readonly string $title,
    )
    {

    }

    public static function createFromArray(array $array): static
    {
        return new static(
            id: $array['id'],
            title: $array['title'],
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function toMobileScheme(TransformMobile $transformer = new IssueTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }
}
