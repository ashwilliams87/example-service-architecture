<?php

namespace App\Http\Requests\Journal;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;

class JournalListRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): DocumentsTypeRequestDTO
    {
        return new DocumentsTypeRequestDTO(
            categoryId: $this->query('category') ?? 0,
            subCategoryId: $this->query('subcategory') ?? 0,
            publisherId: $this->query('publisher') ?? 0,
            limit: $this->query('limit') ?? 0,
            offset: $this->query('offset') ?? 0
        );
    }
}
