<?php

namespace App\Http\Requests\Auth;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Auth\LoginByEmailRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Auth\Requests\LoginByEmailRequestDTO;

class LoginRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): LoginByEmailRequestDTOInterface
    {
        return new LoginByEmailRequestDTO(
            email: $this->input('email'),
            password: $this->input('password'),
        );
    }
}
