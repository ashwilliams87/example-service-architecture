<?php

namespace App\Http\Requests\Statistic;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Statistic\StatisticItemsDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Statistic\Requests\LogReadStatisticRequestDTO;

class LogReadStatisticRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): StatisticItemsDTOInterface
    {
        $request = $this->json()->all();
        return new LogReadStatisticRequestDTO(
            statisticItems: $request['statistics'] ?? [],
        );
    }
}
