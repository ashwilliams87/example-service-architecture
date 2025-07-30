<?php

namespace App\Http\Requests\Auth;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Auth\InviteRequestDTOInterface;
use Lan\Contracts\Transformers\Request\ToDTORequest;
use Lan\DTOs\Auth\Requests\InviteRequestDTO;

class CheckInviteCodeRequest extends EbsFormRequest implements ToDTORequest
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): InviteRequestDTOInterface
    {
        return InviteRequestDTO::create($this->input('invite') ?? '');
    }
}
