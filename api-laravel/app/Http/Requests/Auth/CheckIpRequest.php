<?php

namespace App\Http\Requests\Auth;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Auth\IpAddressDTOInterface;
use Lan\Contracts\Transformers\Request\ToDTORequest;
use Lan\DTOs\Auth\Requests\IpRequestDTO;

class CheckIpRequest extends EbsFormRequest implements ToDTORequest
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): IpAddressDTOInterface
    {
        return new IpRequestDTO(
            ipAddress: $this->ip() ?? '',
        );
    }
}
