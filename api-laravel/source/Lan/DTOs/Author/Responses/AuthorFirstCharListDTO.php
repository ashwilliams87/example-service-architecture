<?php

namespace Lan\DTOs\Author\Responses;

use Lan\Contracts\DTOs\Collection\LanDTOListInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DTOs\Collection\ListDTO;
use Lan\Transformers\Author\AuthorCharacterMapTransformer;

class AuthorFirstCharListDTO extends ListDTO implements LanDTOListInterface, LanDTOInterface, Mobile
{
    public function toMobileScheme(TransformMobile $transformer = new AuthorCharacterMapTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    protected function __construct(AuthorFirstCharDTO...$dtoCollection)
    {
        parent::__construct(...$dtoCollection);
    }

    #[\Override]
    public static function getItemClass()
    {
        return AuthorFirstCharDTO::class;
    }
}
