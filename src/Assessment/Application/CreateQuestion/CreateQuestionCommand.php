<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion;

use DateTimeImmutable;

final class CreateQuestionCommand
{
    private string $text;

    private DateTimeImmutable $createdAt;

    /** string[] */
    private array $choices;

    public function __construct(string $text, DateTimeImmutable $createdAt, array $choices)
    {
        $this->text      = $text;
        $this->createdAt = $createdAt;
        $this->choices   = $choices;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function choices(): array
    {
        return $this->choices;
    }
}