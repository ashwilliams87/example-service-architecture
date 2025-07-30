<?php

namespace Lan\Contracts\DTOs;

interface Countable
{
    public function getCount(): int;
    public function setCount(int $count): static;
}
