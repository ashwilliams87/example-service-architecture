<?php

namespace App\Http\Controllers\Ebs;


use App\Http\Controllers\EbsController;
use App\Http\Requests\Author\AuthorCharacterMapRequest;
use Lan\Contracts\Services\ApiResponseServiceInterface;
use Lan\Contracts\Services\AuthorServiceInterface;

class AuthorController extends EbsController
{
    public function __construct(
        public AuthorServiceInterface      $authorService,
        public ApiResponseServiceInterface $apiResponseService
    )
    {

    }

    public function authorCharacterMap(AuthorCharacterMapRequest $request)
    {
        return $this->apiResponseService->makeSuccessResponseWithObject(
            $this->authorService->getAuthorCharacterMap($request->toDTO())
        );
    }
}
