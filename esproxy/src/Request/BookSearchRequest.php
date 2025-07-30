<?php

namespace App\Request;

use App\DTO\Request\BookSearchDTO;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;


class BookSearchRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "Поле search_query не может быть пустым.")]
        #[Assert\Type(type: 'string', message: "Поле search_query должно быть строкой.")]
        #[SerializedName("search_query")]
        public readonly ?string $searchQuery,

        #[Assert\Type('integer', message: "Limit must be an integer")]
        #[Assert\PositiveOrZero(message: "Limit must be a positive integer or zero")]
        #[Assert\LessThanOrEqual(1000, message: "Limit must be less than or equal to 1000")]
        #[SerializedName("limit")]
        private int            $limit = 10
    )
    {

    }

    public function toDTO(): BookSearchDTO
    {
        return (new BookSearchDTO($this->searchQuery, $this->limit, 0));
    }
}
