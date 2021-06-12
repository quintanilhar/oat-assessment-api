<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion;

use Quintanilhar\AssessmentApi\Assessment\Domain\Choice;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choices;
use Quintanilhar\AssessmentApi\Assessment\Domain\Question;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionRepository;

final class CreateQuestionService
{
    private QuestionRepository $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function __invoke(CreateQuestionCommand $command): void
    {
        $choices = new Choices(
            ...array_map(
                fn (string $text) => Choice::fromString($text),
                $command->choices()
            )
        );

        $question = new Question(
            $this->questionRepository->nextIdentity(),
            $command->text(),
            $command->createdAt(),
            $choices
        );

        $this->questionRepository->save($question);
    }
}
