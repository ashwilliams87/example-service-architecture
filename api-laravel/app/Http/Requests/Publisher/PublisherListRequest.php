<?php

namespace App\Http\Requests\Publisher;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;

class PublisherListRequest extends EbsFormRequest implements TransformableToDTO
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
            type: (int)$this->query('type') ?? 0,
            categoryId: $this->query('catId') ?? 0,
            subCategoryId: $this->query('subCatId') ?? 0,
            limit: $this->query('limit') ?? 0,
            offset: $this->query('offset') ?? 0,
            syntex: $this->query('syntex') ?? 0,
            author: $this->query('author') ?? '',
        );
    }
}
