<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Domain;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class Question
{
    private const EXPECTED_CHOICES_AMOUNT = 3;

    private QuestionId $id;

    private string $text;

    private DateTimeImmutable $createdAt;

    private Choices $choices;

    public function __construct(
        QuestionId $id, 
        string $text, 
        DateTimeImmutable $createdAt, 
        Choices $choices
    ) {
        Assert::count($choices, self::EXPECTED_CHOICES_AMOUNT, 'Question must containt exactly %s choices');

        $this->id        = $id;
        $this->text      = $text;
        $this->createdAt = $createdAt;
        $this->choices   = $choices;
    }

    public function id(): QuestionId
    {
        return $this->id;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function choices(): Choices
    {
        return $this->choices;
    }
}
