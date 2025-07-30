<?php

namespace App\Http;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\DataTypes\RequestResult\Error\BadRequest;

class EbsFormRequest extends FormRequest implements ValidatesWhenResolved
{
    public function __construct(
        private ApiResponseServiceInterface $apiResponseService,
        array                               $query = [],
        array                               $request = [],
        array                               $attributes = [],
        array                               $cookies = [],
        array                               $files = [],
        array                               $server = [],
                                            $content = null,
    )
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    protected function failedValidation(Validator $validator): void
    {
        $data = [
            'errors' => $validator->errors(),
            'request' => $this->all()
        ];

        $response = $this->apiResponseService->makeErrorResponseWithObject($data, new BadRequest('Ошибка валидации запроса'));
        throw new HttpResponseException($response);
    }
}
