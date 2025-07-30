<?php

namespace Lan\Contracts\Repositories;

use Lan\Enums\DocumentType;
use Lan\Contracts\DTOs\Document\DocumentsTypeRequestDTOInterface;

interface SearchRepositoryInterface
{
    const int FOUND_IN_BOOKS = 1;
    const int FOUND_IN_BOOK_TITLES = 2;
    const int FOUND_IN_BOOK_AUTHORS = 3;
    const int FOUND_IN_BOOK_TEXT = 8;
    const int FOUND_IN_BOOK_CONTENTS = 10;
    const int FOUND_IN_JOURNAL_TITLES = 4;
    const int FOUND_IN_ARTICLE_TITLES = 6;
    const int FOUND_IN_ARTICLE_AUTHORS = 5;
    const int FOUND_IN_ARTICLES_BY_KEYWORDS = 7;
    const int FOUND_IN_ARTICLE_TEXTS = 9;
    const array SEARCH_TYPE_TO_RESULTS_TYPE = [
        self::FOUND_IN_BOOK_TITLES => [
            'field' => 'name',
            'entity' => DocumentType::BOOK->value,
            'name' => 'Найдено в названиях книг'
        ],
        self::FOUND_IN_BOOK_AUTHORS => [
            'field' => 'author',
            'entity' => DocumentType::BOOK->value,
            'name' => 'Найдено в авторах книг'
        ],
        self::FOUND_IN_BOOKS => [
            'field' => 'book',
            'entity' => DocumentType::BOOK->value,
            'name' => 'Найдено в книгах'
        ],
        self::FOUND_IN_BOOK_TEXT => [
            'field' => 'text',
            'entity' => DocumentType::BOOK->value,
            'name' => 'Найдено в текстах книг'
        ],
        self::FOUND_IN_BOOK_CONTENTS => [
            'field' => 'toc',
            'entity' => DocumentType::BOOK->value,
            'name' => 'Найдено в оглавлении книг'
        ],
        self::FOUND_IN_JOURNAL_TITLES => [
            'field' => 'name',
            'entity' => DocumentType::JOURNAL->value,
            'name' => 'Найдено в названиях журналов'
        ],
        self::FOUND_IN_ARTICLE_AUTHORS => [
            'field' => 'author',
            'entity' => DocumentType::ARTICLE->value,
            'name' => 'Найдено в авторах статей'
        ],
        self::FOUND_IN_ARTICLE_TITLES => [
            'field' => 'name',
            'entity' => DocumentType::ARTICLE->value,
            'name' => 'Найдено в заголовках статей'
        ],

        self::FOUND_IN_ARTICLES_BY_KEYWORDS => [
            'field' => 'keyword',
            'entity' => DocumentType::ARTICLE->value,
            'name' => 'Найдено в статьях по ключевым словам'
        ],
        self::FOUND_IN_ARTICLE_TEXTS => [
            'field' => 'text',
            'entity' => DocumentType::ARTICLE->value,
            'name' => 'Найдено в текстах статей'
        ]
    ];

    const BOOK_SEARCH_TYPE_LIST = [
        self::FOUND_IN_BOOK_TITLES,
        self::FOUND_IN_BOOK_AUTHORS,
        self::FOUND_IN_BOOKS,
        self::FOUND_IN_BOOK_TEXT,
        self::FOUND_IN_BOOK_CONTENTS,
    ];

    const JOURNAL_SEARCH_TYPE_LIST = [
        self::FOUND_IN_JOURNAL_TITLES,
    ];

    const ARTICLE_SEARCH_TYPE_LIST = [
        self::FOUND_IN_ARTICLE_TITLES,
        self::FOUND_IN_ARTICLE_AUTHORS,
        self::FOUND_IN_ARTICLES_BY_KEYWORDS,
        self::FOUND_IN_ARTICLE_TEXTS,
    ];

    public function searchAll(DocumentsTypeRequestDTOInterface $requestDTO): array;

    public function searchBooks(DocumentsTypeRequestDTOInterface $requestDTO): array;

    public function searchJournals(DocumentsTypeRequestDTOInterface $requestDTO): array;
    public function searchArticles(DocumentsTypeRequestDTOInterface $requestDTO): array;
}
