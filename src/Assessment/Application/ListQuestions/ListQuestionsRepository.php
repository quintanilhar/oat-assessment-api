<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions;

interface ListQuestionsRepository
{
    /**
     * @return QuestionForList[]
     */
    public function all(): array;
}