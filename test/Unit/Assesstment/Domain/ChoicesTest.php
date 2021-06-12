<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Test\Unit\Assesstment\Domain;

use PHPUnit\Framework\TestCase;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choice;
use Quintanilhar\AssessmentApi\Assessment\Domain\Choices;

class ChoicesTest extends TestCase
{
    public function testCountsChoices(): void
    {
        $choices = new Choices(
            Choice::fromString('Choice 1'),
            Choice::fromString('Choice 2')
        );

        $this->assertCount(2, $choices);
    }
}