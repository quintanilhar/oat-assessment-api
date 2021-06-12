<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Test\Unit\Assesstment\Domain;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choice;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choices;
use Quintanilhar\AssessmentApi\Assessment\Domain\Question;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionId;
use Ramsey\Uuid\Uuid;

class QuestionTest extends TestCase
{
    public function testRetrievesAttributes(): void
    {
        $questionId = $this->createQuestionId();

        $question = new Question(
            $questionId,
            'What is a question?',
            new DateTimeImmutable(),
            $this->fakeChoices()
        );

        $this->assertSame($questionId, $question->id());
        $this->assertSame('What is a question?', $question->text());
        $this->assertCount(3, $question->choices());
    }

    public function testThrowsWhenChoicesAmountDoesntMatch(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Question must containt exactly');

        new Question(
            $this->createQuestionId(),
            'What is a question?',
            new DateTimeImmutable(),
            $this->fakeChoices(2)
        );
    }

    private function createQuestionId(): QuestionId
    {
        return QuestionId::fromString(Uuid::uuid4()->toString());
    }

    private function fakeChoices(int $amount = 3): Choices
    {
        $choices = [];
        for ($i = 1; $i <= $amount; $i++) {
            $choices[] = Choice::fromString('Choice ' . $i);
        }

        return new Choices(...$choices);
    }
}
