<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Domain;

use Webmozart\Assert\Assert;

final class Choice
{
    private string $text;

    private function __construct(string $text)
    {
        Assert::notEmpty($text, 'Choice must contain at least 1 character');

        $this->text = $text;
    }

    public static function fromString(string $text): self
    {
        return new self($text);
    }

    public function asString(): string
    {
        return $this->text;
    }
}