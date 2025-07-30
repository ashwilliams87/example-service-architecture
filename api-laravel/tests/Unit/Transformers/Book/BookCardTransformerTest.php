<?php

namespace Tests\Unit\Transformers\Book;

use Codeception\Test\Unit;
use Lan\DTOs\Book\Responses\BookCardDTO;
use Lan\Transformers\Book\BookCardTransformer;


class BookCardTransformerTest extends Unit
{
    public function testTransformToMobileScheme(): void
    {
        $bookDto = BookCardDTO::createFromIceQueryResultRow([
                'id' => 1,
                'isbn' => '978-3-16-148410-0',
                'edition' => 'First Edition',
                'pages' => 350,
                'book_private' => true,
                'synthesizer_quality' => 5,
                'title' => 'Sample Book Title',
                'description' => 'This is a test description for the book.',
                'author' => 'John Doe',
                'year' => 2024,
                'available' => true,
                'hasPdf' => true,
                'hasEpub' => false,
                'hasSyntex' => true,
                'hasAudio' => false,
                'publisher' => 'Test Publisher',
                'synthesizer_editor' => 'Editor Name',
                'txt' => 'Sample text content',
                'expired_date' => '2025-12-31',
                'book_expired' => '2026-12-31',
            ]);

        $expectedTransformResult = [
            'id' => 1,
            'isbn' => '978-3-16-148410-0',
            'edition' => 'First Edition',
            'pages' => '350',
            'book_private' => 1,
            'synthesizer_quality' => 5,
            'title' => 'Sample Book Title',
            'description' => 'This is a test description for the book.',
            'author' => 'John Doe',
            'year' => 2024,
            'hasPdf' => true,
            'hasEpub' => false,
            'hasSyntex' => true,
            'hasAudio' => false,
            'publisher' => 'Test Publisher',
            'synthesizer_editor' => 'Editor Name',
            'expired_date' => '2025-12-31',
            'publisher__fk' => 0,
            'active' => false,
            'expired' => null,
            'access_date' => null,
            'cover' => 'ebs.test.ru/img/cover/book/1.jpg',
            'audio' => false,
            'size' => 0,
        ];

        $transformedBookCardDto = (new BookCardTransformer())->transformToMobileScheme($bookDto);

        $this->assertEquals($expectedTransformResult, $transformedBookCardDto);
    }
}
