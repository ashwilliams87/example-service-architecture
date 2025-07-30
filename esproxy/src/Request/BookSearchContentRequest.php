<?php

namespace App\Request;

use App\Contract\DTO\Request\DocumentContentSearchDTOInterface;
use App\Contract\Request\TransformableToDTO;
use App\DTO\Request\BookContentSearchDTO;
use Ice\Core\Debuger;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class BookSearchContentRequest implements TransformableToDTO
{
    #[Assert\All([
        new Assert\Type(type: 'integer', message: 'Each book ID must be an integer')
    ])]
    #[Assert\Type('array', message: 'Book IDs must be an array')]
    #[SerializedName("book_ids")]
    private array $bookIds;

    #[Assert\NotBlank(message: "Similarity cutoff cannot be blank")]
    #[Assert\Range(
        min: 0,
        max: 1,
        notInRangeMessage: "Similarity cutoff must be between {min} and {max}"
    )]
    #[SerializedName("similarity_cutoff")]
    private float $similarityCutoff;

    #[Assert\NotBlank(message: "Search query cannot be blank")]
    #[Assert\Type('string', message: "Search query must be a string")]
    #[SerializedName("search_query")]
    private string $searchQuery;

    #[Assert\Type('integer', message: "Limit must be an integer")]
    #[Assert\PositiveOrZero(message: "Limit must be a positive integer or zero")]
    #[Assert\LessThanOrEqual(1000, message: "Limit must be less than or equal to 1000")]
    #[SerializedName("limit")]
    private int $limit;

    public function __construct(
        array  $bookIds = [],
        float  $similarityCutoff = 0.0,
        string $searchQuery = '',
        int    $limit = 10,
    )
    {
        $this->bookIds = $bookIds;
        $this->similarityCutoff = $similarityCutoff;
        $this->searchQuery = $searchQuery;
        $this->limit = $limit;
    }

    public function toDTO(): DocumentContentSearchDTOInterface
    {
        return new BookContentSearchDTO(
            bookIds: $this->bookIds,
            similarityCutoff: $this->similarityCutoff,
            searchQuery: $this->searchQuery,
            limit: $this->limit
        );
    }

    // Геттеры для доступа к свойствам (если нужно)
    public function getBookIds(): array
    {
        return $this->bookIds;
    }

    public function getSimilarityCutoff(): float
    {
        return $this->similarityCutoff;
    }

    public function getSearchQuery(): string
    {
        return $this->searchQuery;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}