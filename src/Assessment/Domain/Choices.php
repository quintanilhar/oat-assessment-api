<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Domain;

use Countable;

final class Choices implements Countable
{
    private array $items;

    public function __construct(Choice ...$items)
    {
        $this->items = $items;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
