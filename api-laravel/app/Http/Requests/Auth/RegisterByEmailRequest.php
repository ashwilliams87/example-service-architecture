<?php

namespace App\Http\Requests\Auth;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Auth\RegisterUserRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Auth\Requests\RegisterByEmailRequestDTO;

class RegisterByEmailRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): RegisterUserRequestDTOInterface
    {
        list($lastname, $firstname, $patronymic) = array_pad(explode(' ', trim($this->input('name'))), 3, '');

        return new RegisterByEmailRequestDTO(
            lastName: $lastname,
            firstName: $firstname,
            patronymic: $patronymic,
            email: $this->input('email'),
            password: $this->input('password'),
            ipAddress: $this->ip() ?? '',
        );
    }
}
