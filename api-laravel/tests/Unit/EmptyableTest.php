<?php

namespace Tests\Unit;

use Codeception\Test\Unit;
use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\DataTypes\EbsCarbon;
use Lan\DTOs\Auth\Responses\UserAuthCardDTO;
use Lan\DTOs\Book\Responses\BookCardDTO;
use Lan\DTOs\Book\Responses\BookItemResponseDTO;
use Lan\DTOs\Journal\Responses\JournalIssueList\JournalIssuesCardDTO;

class EmptyableTest extends Unit
{
    /**
     * A basic test example.
     */
    public function testIsEmptyDate(): void
    {
        $emptyEbsDate = EbsCarbon::create(0);
        $this->assertInstanceOf(EmptyableInterface::class, $emptyEbsDate);
        $this->assertTrue($emptyEbsDate->isEmpty());
    }

    public function testIsEmptyBookCardResponseDTO(): void
    {
        $bookDTOResponse = BookCardDTO::create();
        $this->assertInstanceOf(EmptyableInterface::class, $bookDTOResponse);
        $this->assertTrue($bookDTOResponse->isEmpty());
    }

    public function testIsEmptyUserResponseDTO(): void
    {
        $userDTOResponse = UserAuthCardDTO::create();
        $this->assertInstanceOf(EmptyableInterface::class, $userDTOResponse);
        $this->assertTrue($userDTOResponse->isEmpty());
    }

    public function testIsEmptyBookListResponseDTO(): void
    {
        $bookListResponseDTO = BookItemResponseDTO::create();
        $this->assertInstanceOf(EmptyableInterface::class, $bookListResponseDTO);
        $this->assertTrue($bookListResponseDTO->isEmpty());
    }

    public function testIsEmptyJournalIssueListResponseDTO(): void
    {
        $bookListResponseDTO = JournalIssuesCardDTO::create();
        $this->assertInstanceOf(EmptyableInterface::class, $bookListResponseDTO);
        $this->assertTrue($bookListResponseDTO->isEmpty());
    }
}
