<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Domain;

interface QuestionRepository
{
    public function save(Question $question): void;

    public function nextIdentity(): QuestionId;
}
