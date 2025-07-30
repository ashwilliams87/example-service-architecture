<?php

namespace App\Http\Requests\Document;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Document\DocumentIdRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Document\DocumentIdRequestDTO;

class DocumentMetaRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): DocumentIdRequestDTOInterface
    {
        return new  DocumentIdRequestDTO(
            id: $this->query('id') ?? 0,
        );
    }
}
