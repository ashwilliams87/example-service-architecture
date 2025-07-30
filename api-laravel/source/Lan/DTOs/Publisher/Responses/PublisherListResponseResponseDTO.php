<?php

namespace Lan\DTOs\Publisher\Responses;

use Ice\Core\QueryResult;
use Lan\Contracts\DTOs\Publisher\PublisherListResponseDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Publisher\PublisherListTransformer;

class PublisherListResponseResponseDTO extends ListDTO implements PublisherListResponseDTOInterface
{
    protected function __construct(PublisherDTO...$dtoCollection)
    {
        parent::__construct(...$dtoCollection);
    }

    #[\Override]
    public static function getItemClass()
    {
        return PublisherDTO::class;
    }

    #[\Override]
    public static function createFromIceQueryResultRows(QueryResult $queryBuilder): static
    {
        $dto = parent::createFromIceQueryResultRows($queryBuilder);

        if ($dto instanceof PublisherListResponseResponseDTO) {
            return $dto;
        }

        //TODO: изменить на возврат пустого ДТО со статусом ошибка
        throw new \Exception('Bad code. Service returne not BookCardListResponseDTOInterface');
    }

    public function toMobileScheme(TransformMobile $transformer = new PublisherListTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }
}
