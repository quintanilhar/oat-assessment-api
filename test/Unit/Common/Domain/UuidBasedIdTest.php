<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Test\Unit\Common\Domain;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UuidBasedIdTest extends TestCase
{
    public function testCreatesFromString(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $id = FakeUuidBasedId::fromString($uuid);

        $this->assertEquals($uuid, $id->asString());
    }

    public function testConvertsToString(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $id = FakeUuidBasedId::fromString($uuid);

        $this->assertEquals($uuid, (string)$id);
    }
}