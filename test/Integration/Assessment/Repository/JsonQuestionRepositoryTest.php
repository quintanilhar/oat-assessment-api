<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Test\Integration\Assessment\Repository;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\QuestionForList;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choice;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choices;
use Quintanilhar\AssessmentApi\Assessment\Domain\Question;
use Quintanilhar\AssessmentApi\Assessment\Infrastructure\Repository\JsonQuestionRespository;
use RuntimeException;

class JsonQuestionRepositoryTest extends TestCase
{
    private const DB_PATH = __DIR__ . '/../../../../../var/integration_test_question_repository.json';

    private JsonQuestionRespository $sut;

    public function setUp(): void
    {
        if (file_exists(self::DB_PATH)) {
            unlink(self::DB_PATH);
        }

        $this->sut = new JsonQuestionRespository(self::DB_PATH);
    }

    public function testStoresOneQuestion(): void
    {
        $question = $this->createQuestion('How many planets we have in our solar system?', ['Five', 'Ten', 'Eight']);

        $this->sut->save($question);

        $this->assertContainsRecords(1);

        $this->assertDbContainsQuestion($question);
    }

    public function testStoresMultipleQuestions(): void
    {
        $questions = [];

        for ($i = 1; $i <= 5; $i++) {
            $question = $this->createQuestion('What choices do you have for question ' . $i . '?');

            $this->sut->save($question);

            $questions[] = $question;
        }

        $this->assertContainsRecords(5);
        foreach ($questions as $question) {
            $this->assertDbContainsQuestion($question);
        }
    }

    public function testReturnsEmptyListWhenDbDoesntExist(): void
    {
        $sut = new JsonQuestionRespository(__DIR__ . '/non_existing_db.json');

        $this->assertCount(0, $sut->all());
    }

    public function testListAllQuestions(): void
    {
        $questions = [
            $this->createQuestion('What is the odd number?', ['3', '4', '8']),
            $this->createQuestion('What is the even number?', ['1', '3', '6'])
        ];

        foreach ($questions as $question) {
            $this->sut->save($question);
        }

        $questionsForList = $this->sut->all();

        $this->assertCount(2, $questionsForList);

        $this->assertQuestionForListMatchesQuestion($questions[0], $questionsForList[0]);
        $this->assertQuestionForListMatchesQuestion($questions[1], $questionsForList[1]);
    }

    public function testThrowsWhenInvalidDirectoryIsProvided(): void
    {
        $this->expectException(RuntimeException::class);

        $sut = new JsonQuestionRespository(__DIR__ . '/invalid-path/db.json');

        $sut->save($this->createQuestion('Something?'));
    }

    private function createQuestion(string $text, array $choices = []): Question
    {
        return new Question(
            $this->sut->nextIdentity(),
            $text,
            new DateTimeImmutable(),
            new Choices(
                Choice::fromString($choices[0] ?? 'Choice 1'),
                Choice::fromString($choices[1] ?? 'Choice 2'),
                Choice::fromString($choices[2] ?? 'Choice 3'),
            )
        );
    }

    private function assertContainsRecords(int $expectedNumberOfRecords): void
    {
        $records = json_decode(file_get_contents(self::DB_PATH), true);

        $this->assertCount($expectedNumberOfRecords, $records);
    }

    private function assertDbContainsQuestion(Question $question): void
    {
        $records = json_decode(file_get_contents(self::DB_PATH), true);

        $expectedChoices = array_map(
            fn (Choice $choice) => $choice->asString(), 
            $question->choices()->toArray()
        );

        foreach ($records as $record) {
            if ($record['id'] !== $question->id()->asString()) {
                continue;
            }

            $this->assertSame($question->id()->asString(), $record['id']);
            $this->assertSame($question->text(), $record['text']);
            $this->assertSame($question->createdAt()->format(DateTimeInterface::RFC3339), $record['createdAt']);
            $this->assertSame($expectedChoices, $record['choices']);

            return;
        }

        $this->fail('Question can\'t be found in the db.');
    }

    private function assertQuestionForListMatchesQuestion(Question $question, QuestionForList $questionForList): void
    {
        $this->assertSame($question->text(), $questionForList->text());
        $this->assertSame($question->createdAt()->format(DateTimeInterface::RFC3339), $questionForList->createdAt());

        $questionChoices = array_map(
            fn (Choice $choice) => $choice->asString(), 
            $question->choices()->toArray()
        );

        $this->assertSame($questionChoices, $questionForList->choices());
    }
}