<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Common\Infrastructure;

use Quintanilhar\AssessmentApi\Common\Application\TranslateSentenceService;
use Stichoza\GoogleTranslate\GoogleTranslate;

final class GoogleTranslateSentenceService implements TranslateSentenceService
{
    private GoogleTranslate $translator;

    public function __construct(GoogleTranslate $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke(string $sentence): string
    {
        return $this->translator->translate($sentence);
    }
}
