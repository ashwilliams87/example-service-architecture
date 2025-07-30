<?php

namespace App\Http\Requests\Category;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;

class CategoryListRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): DocumentsTypeRequestDTOInterface
    {
        $author = '';
        if ($this->query('author')) {
            $author = strtoupper($this->query('author'));
        }

        return new DocumentsTypeRequestDTO(
            categoryId: $this->query('category') ?? 0,
            subCategoryId: $this->query('subcategory') ?? 0,
            publisherId: $this->query('publisher') ?? 0,
            limit: $this->query('limit') ?? 0,
            offset: $this->query('offset') ?? 0,
            syntex: $this->query('syntex') ?? 0,
            author: $author,
            sortingField: $this->query('sort') ?? ''
        );
    }
}
