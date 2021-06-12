<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Common\Domain;

use Webmozart\Assert\Assert;

trait UuidBasedIdTrait
{
    private string $id;

    private function __construct(string $id)
    {
        Assert::uuid($id, 'Id must be a valid uuid');

        $this->id = $id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->asString();
    }
}