<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Infrastructure\Repository;

use DateTimeInterface;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\ListQuestionsRepository;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\QuestionForList;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choice;
use Quintanilhar\AssessmentApi\Assessment\Domain\Question;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionId;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionRepository;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class JsonQuestionRespository implements QuestionRepository, ListQuestionsRepository
{
    private string $dbPath;

    public function __construct(string $dbPath)
    {
        $this->dbPath = $dbPath;
    }

    public function save(Question $question): void
    {
        $questions = $this->loadFromFile();

        $questions[] = [
            'id'        => $question->id()->asString(),
            'text'      => $question->text(),
            'createdAt' => $question->createdAt()->format(DateTimeInterface::RFC3339),
            'choices'   => array_map(
                fn (Choice $choice) => $choice->asString(), 
                $question->choices()->toArray()
            )
        ];

        $this->saveInFile($questions);
    }

    public function nextIdentity(): QuestionId
    {
        return QuestionId::fromString(Uuid::uuid4()->toString());
    }

    public function all(): array
    {
        $questions = [];

        foreach ($this->loadFromFile() as $record) {
            $questions[] = QuestionForList::fromArray($record);
        }

        return $questions;
    }

    private function loadFromFile(): array
    {
        if (!file_exists($this->dbPath)) {
            return [];
        }

        return json_decode(file_get_contents($this->dbPath), true);
    }

    private function saveInFile(array $questions): void
    {
        $directory = dirname($this->dbPath);

        if (!is_dir($directory)) {
            throw new RuntimeException(sprintf(
                'Directory "%s" does not exist yet and could not be created',
                $directory
            ));
        }

        if (!is_writable($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" is not writable', $directory));
        }

        file_put_contents($this->dbPath, json_encode($questions));
    }
}
