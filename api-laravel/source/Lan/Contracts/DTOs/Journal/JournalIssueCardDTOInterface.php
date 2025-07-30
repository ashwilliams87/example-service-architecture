<?php

namespace Lan\Contracts\DTOs\Journal;

use Lan\Contracts\DTOs\LanDTOInterface;
use Lan\Contracts\DTOs\Mobile;
use Lan\DTOs\Journal\Responses\JournalIssueList\YearWithIssueListDTO;

interface JournalIssueCardDTOInterface extends LanDTOInterface, Mobile
{
public function getId(): int;

    public function getEdition(): string;

    public function getDescription(): string;

    public function getTitle(): string;

    public function getPublisher(): string;

    public function isAvailable(): bool;

    public function isActive(): bool;

    public function getIssuePerYear(): string;

    public function getIssn(): string;

    public function getCountry(): string;

    public function getCity(): string;

    public function getEmail(): string;

    public function getYear(): string;

    public function isInVac(): bool;

    public function getCoverUrl(): string;

    public function getYearsWithIssuesDTO(): YearWithIssueListDTO;
}
