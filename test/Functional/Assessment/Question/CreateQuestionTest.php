<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Test\Functional\Assessment\Question;

use DateTimeImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion\CreateQuestionCommand;
use Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion\CreateQuestionService;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionId;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionRepository;
use Ramsey\Uuid\Uuid;

class CreateQuestionTest extends TestCase
{
    public function testCreatesQuestion(): void
    {
        /** @var QuestionRepository|MockObject $questionRepository */
        $questionRepository = $this->createMock(QuestionRepository::class);
        $questionRepository->method('nextIdentity')
            ->willReturn(QuestionId::fromString(Uuid::uuid4()->toString()));

        $questionRepository->expects($this->once())
            ->method('save');

        $command = new CreateQuestionCommand(
            'What is the name of the first Iron Man\'s AI assistant?',
            new DateTimeImmutable('now'),
            [
                'PAMELA',
                'JARVIS',
                'FRIDAY'
            ]
        );

        $createQuestionService = new CreateQuestionService($questionRepository);

        $createQuestionService($command);
    }
}
