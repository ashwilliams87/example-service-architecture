<?php

namespace App\Http\Requests\Book;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Document\DocumentIdRequestDTO;

class BookCardRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): DocumentIdRequestDTOInterface
    {
        return new DocumentIdRequestDTO(
            id: $this->query('id')
        );
    }
}
