<?php

namespace Lan\Contracts\DTOs\Publisher;

use Lan\Contracts\DTOs\CreatableFromIceQueryResultRow;
use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;

interface PublisherDTOInterface extends LanDTOInterface, CreatableFromIceQueryResultRow, Mobile
{

}
