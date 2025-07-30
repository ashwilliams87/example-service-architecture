<?php

namespace App\Http\Requests\Auth;

use App\Http\EbsFormRequest;
use Lan\Contracts\DTOs\Auth\LoginBySocialRequestDTOInterface;
use Lan\Contracts\DTOs\TransformableToDTO;
use Lan\DTOs\Auth\Requests\LoginBySocialRequestDTO;

class LoginBySocialRequest extends EbsFormRequest implements TransformableToDTO
{
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function toDTO(): LoginBySocialRequestDTOInterface
    {
        return new LoginBySocialRequestDTO(
            socialNetwork: $this->input('network'),
            socialToken: $this->input('token_social'),
        );
    }
}
