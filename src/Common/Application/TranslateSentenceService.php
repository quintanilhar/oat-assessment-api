<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Common\Application;

interface TranslateSentenceService
{
    public function __invoke(string $sentence): string;
}
