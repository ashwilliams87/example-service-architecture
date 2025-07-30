<?php

namespace Lan\DTOs\Journal\Responses\JournalIssueList;

use Lan\Contracts\DataTypes\Emptyable\EmptyableInterface;
use Lan\Contracts\DTOs\CreatableFromArray;
use Lan\Contracts\DTOs\Journal\JournalIssueCardDTOInterface;
use Lan\Contracts\Transformers\TransformMobile;
use Lan\DataTypes\EmptyableState\EmptyDTOId;
use Lan\Transformers\Journal\JournalIssuesCardTransformer;

class JournalIssuesCardDTO implements JournalIssueCardDTOInterface, EmptyableInterface, CreatableFromArray
{
    private function __construct(
        private readonly int                  $id,
        private readonly string               $edition,
        private readonly string               $description,
        private readonly string               $title,
        private readonly string               $publisher,
        private readonly bool                 $available,
        private readonly bool                 $active,
        private readonly string               $issuePerYear,
        private readonly string               $issn,
        private readonly string               $country,
        private readonly string               $city,
        private readonly string               $email,
        private readonly string               $year,
        private readonly bool                 $inVac,
        private readonly string               $coverUrl,

        private readonly YearWithIssueListDTO $yearsWithIssuesDTO,
        private readonly EmptyableInterface   $emptyableIdState = new EmptyDTOId(),
    )
    {

    }

    public static function create(
        int                           $id = 0,
        string                        $edition = '',
        string                        $description = '',
        string                        $title = '',
        string                        $publisher = '',
        bool                          $available = false,
        bool                          $active = false,
        string                        $issuePerYear = '',
        string                        $issn = '',
        string                        $country = '',
        string                        $city = '',
        string                        $email = '',
        string                        $year = '',
        bool                          $inVac = false,
        string                        $coverUrl = '',
        YearWithIssueListDTO $yearsWithIssues = null,
    ): static
    {
        return new self(
            id: $id,
            edition: $edition,
            description: $description,
            title: $title,
            publisher: $publisher,
            available: $available,
            active: $active,
            issuePerYear: $issuePerYear,
            issn: $issn,
            country: $country,
            city: $city,
            email: $email,
            year: $year,
            inVac: $inVac,
            coverUrl: $coverUrl,
            yearsWithIssuesDTO: $yearsWithIssues ?? YearWithIssueListDTO::createFromArrayList([]),
        );
    }


    public function isEmpty(null $verifiable = null): bool
    {
        return $this->emptyableIdState->isEmpty($this->getId());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEdition(): string
    {
        return $this->edition;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getIssuePerYear(): string
    {
        return $this->issuePerYear;
    }

    public function getIssn(): string
    {
        return $this->issn;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function isInVac(): bool
    {
        return $this->inVac;
    }

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }

    public function getYearsWithIssuesDTO(): YearWithIssueListDTO
    {
        return $this->yearsWithIssuesDTO;
    }

    public function isValid(): bool
    {
        // TODO: Implement isValid() method.
    }

    public function toMobileScheme(TransformMobile $transformer = new JournalIssuesCardTransformer()): array
    {
        return $transformer->transformToMobileScheme($this);
    }

    public static function createFromArray(array $array): static
    {
        return new static(
            id: $array['id'],
            edition: $array['edition'],
            description: $array['description'],
            title: $array['title'],
            publisher: $array['publisher'],
            available: $array['available'],
            active: $array['active'],
            issuePerYear: $array['issueperyear'],
            issn: $array['issn'],
            country: $array['country'],
            city: $array['city'],
            email: $array['email'] ?: '',
            year: $array['year'],
            inVac: $array['vac'],
            coverUrl: $array['cover'],
            yearsWithIssuesDTO: YearWithIssueListDTO::createFromArrayList($array['years']),
        );
    }
}
