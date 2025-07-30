<?php

namespace App\Http\Requests\Search;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\Contracts\Repositories\SearchRepositoryInterface;
use Lan\DTOs\Category\Requests\DocumentsTypeRequestDTO;

class SearchByDocumentTypeRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        $implodedSearchTypes = implode(',', array_merge(
            SearchRepositoryInterface::BOOK_SEARCH_TYPE_LIST,
            SearchRepositoryInterface::JOURNAL_SEARCH_TYPE_LIST,
            SearchRepositoryInterface::ARTICLE_SEARCH_TYPE_LIST,
        ));

        return [
            'type' => ['required', 'in:' . $implodedSearchTypes]
        ];
    }

    public function toDTO(): DocumentsTypeRequestDTOInterface
    {
        return new DocumentsTypeRequestDTO(
            type: $this->query('type') ?? 0,
            categoryId: $this->query('category') ?? 0,
            limit: $this->query('limit') ?? 10,
            offset: $this->query('offset') ?? 0,
            syntex: $this->query('syntex') ?? 0,
            query: $this->query('query') ?? '',
        );
    }
}
