<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Domain;

final class Choice
{
    private ChoiceId $id;

    private string $text;

    public function __construct(ChoiceId $id, string $text)
    {
        $this->id   = $id;
        $this->text = $text;
    }

    public function id(): ChoiceId
    {
        return $this->id;
    }

    public function text(): string
    {
        return $this->text;
    }
}