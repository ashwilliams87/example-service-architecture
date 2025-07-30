<?php

namespace Lan\Contracts\DTOs\Author;

use Lan\Contracts\DTOs\Collection\LanDTOListInterface;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;

interface AuthorCharacterMapResponseDTOInterface extends LanDTOInterface, Mobile
{
    public function getEnglishCharacterListDTO(): LanDTOListInterface;

    public function getRussianCharacterListDTO(): LanDTOListInterface;

    public function getNumericCharacterListDTO(): LanDTOListInterface;
}
