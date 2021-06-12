<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Common\Infrastructure;

final class LanguageRegistry
{
    private string $language;

    public function __construct(string $defaultLanguage)
    {
        $this->language = $defaultLanguage;
    }

    public function set(string $language): void
    {
        $this->language = $language;
    }

    public function get(): string
    {
        return $this->language;
    }
}
