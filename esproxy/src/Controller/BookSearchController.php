<?php

namespace App\Controller;

use App\Contract\Service\BookSearchServiceInterface;
use App\Contract\Service\SecurityServiceInterface;
use App\Request\BookSearchContentRequest;
use App\Request\BookSearchRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class BookSearchController extends AbstractController
{
    public function __construct(
        private readonly BookSearchServiceInterface $bookSearchService,
        private readonly SecurityServiceInterface   $securityService,
        private readonly SerializerInterface        $serializer,
    )
    {

    }

    #[Route('/search/book', name: 'search_book', methods: ['POST'])]
    public function searchBooks(
        #[MapRequestPayload] BookSearchRequest $requestDto
    ): Response
    {
        // Авторизация
        if (!$this->securityService->isAuth() && !$this->securityService->isReader()) {
            return $this->json(['status' => 'error', 'message' => 'Не удалось определить подписчика. Проверьте токен'], Response::HTTP_UNAUTHORIZED);
        }

        $dto = $requestDto->toDTO();
        $dto->setSubscriberId($this->securityService->getSubscriberId());

        $searchBooksCollectionDTO = $this->bookSearchService->searchBookCollection($dto);

        return new JsonResponse(
            $this->serializer->serialize(['books' => $searchBooksCollectionDTO->getDocuments()], JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/search/book/content', name: 'search_book_content', methods: ['POST'])]
    public function searchBookContent(
        #[MapRequestPayload] BookSearchContentRequest $requestDto
    ): Response
    {
        // Авторизация
        if (!$this->securityService->isAuth() && !$this->securityService->isReader()) {
            return $this->json(['status' => 'error', 'message' => 'Не удалось определить подписчика. Проверьте токен'], Response::HTTP_UNAUTHORIZED);
        }

        $dto = $requestDto->toDTO();

        $dto->setSubscriberId($this->securityService->getSubscriberId());

        $result = $this->bookSearchService->searchBookContentCollection($dto);

        return new JsonResponse(
            $this->serializer->serialize(['books' => $result->getDocuments()], JsonEncoder::FORMAT),
            Response::HTTP_OK,
            [],
            true
        );
    }
}