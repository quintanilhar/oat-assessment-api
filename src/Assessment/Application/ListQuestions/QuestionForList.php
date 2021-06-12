<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions;

class QuestionForList
{
    private string $text;

    private string $createdAt;

    /** @var string[] */
    private array $choices;

    private function __construct()
    {
    }

    public static function fromArray(array $record): self
    {
        $question = new self();

        $question->text      = $record['text'] ?? '';
        $question->createdAt = $record['createdAt'] ?? '';
        $question->choices   = $record['choices'] ?? [];

        return $question;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }

    public function choices(): array
    {
        return $this->choices;
    }
}
