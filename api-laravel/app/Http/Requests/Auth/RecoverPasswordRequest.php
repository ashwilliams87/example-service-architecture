<?php

namespace App\Http\Requests\Auth;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Auth\RecoverPasswordRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Auth\Requests\RecoverPasswordRequestDTO;

class RecoverPasswordRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): RecoverPasswordRequestDTOInterface
    {
        return new RecoverPasswordRequestDTO(
            email: $this->input('email') ?? ''
        );
    }
}
