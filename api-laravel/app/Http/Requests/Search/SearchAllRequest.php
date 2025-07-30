<?php

namespace App\Http\Requests\Search;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;

class SearchAllRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): DocumentsTypeRequestDTOInterface
    {
        return new DocumentsTypeRequestDTO(
            categoryId: $this->query('category') ?? 0,
            syntex: $this->query('syntex') ?? 0,
            query: $this->query('query') ?? '',
        );
    }
}
