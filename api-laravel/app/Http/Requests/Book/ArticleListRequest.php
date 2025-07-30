<?php

namespace App\Http\Requests\Book;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Document\DocumentIdRequestDTO;

class ArticleListRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): DocumentIdRequestDTO
    {
        return new DocumentIdRequestDTO(
            id: $this->query('id') ?? 0,
        );
    }
}
