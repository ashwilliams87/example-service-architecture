<?php

namespace App\Http\Requests\Sync;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Sync\DocumentsAndBookmarksAtTimeDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Sync\Requests\DocumentsAndBookmarksAtTimeRequestDTO;

class SyncAllRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): DocumentsAndBookmarksAtTimeDTOInterface
    {
        $request = $this->json()->all();

        return new DocumentsAndBookmarksAtTimeRequestDTO(
            bookmarks: $request['data']['bookmarks'] ?? [],
            books:  $request['data']['books'] ?? [],
            journalArticles: $request['data']['journalArticles'] ?? [],
            deviceTime: (int) $this->input('device_time') ?? 0,
        );
    }
}
