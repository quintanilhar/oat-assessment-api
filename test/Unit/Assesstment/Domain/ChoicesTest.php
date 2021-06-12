<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Test\Unit\Assesstment\Domain;

use PHPUnit\Framework\TestCase;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choice;
use Quintanilhar\AssessmentApi\Assessment\Domain\ChoiceId;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choices;
use Ramsey\Uuid\Uuid;

class ChoicesTest extends TestCase
{
    public function testCountsChoices(): void
    {
        $choices = new Choices(
            $this->fakeChoice(),
            $this->fakeChoice()
        );

        $this->assertCount(2, $choices);
    }

    private function fakeChoice(): Choice
    {
        $randomNumber = mt_rand(10, 100);

        return new Choice(
            ChoiceId::fromString(Uuid::uuid4()->toString()),
            'Choice ' . $randomNumber
        );
    }
}
