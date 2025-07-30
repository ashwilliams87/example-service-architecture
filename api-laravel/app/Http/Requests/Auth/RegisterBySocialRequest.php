<?php

namespace App\Http\Requests\Auth;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Auth\RegisterUserRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Auth\Requests\RegisterBySocialRequestDTO;

class RegisterBySocialRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): RegisterUserRequestDTOInterface
    {
        return new RegisterBySocialRequestDTO(
            lastName: $this->input('lastName'),
            firstName: $this->input('firstName'),
            email: $this->input('email'),
            password: $this->input('password'),
            socialNetwork: $this->input('network'),
            socialToken: $this->input('token_social'),
            inviteCode: $this->input('inviteCode') ?? '',
            ipAddress: $this->ip() ?? '',
        );
    }
}
