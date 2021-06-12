<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1\Validator;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Webmozart\Assert\Assert;

final class CreateQuestionRequestValidator
{
    public function validate(Request $request): array
    {
        $payload = $request->getParsedBody();

        $errors = [];

        try {
            $this->validateText($payload['text'] ?? null);
        } catch (InvalidArgumentException $exception) {
            $errors['text'] = $exception->getMessage();
        }

        try {
            $this->validateChoices($payload['choices'] ?? []);
        } catch (InvalidArgumentException $exception) {
            $errors['choices'] = $exception->getMessage();
        }

        return $errors;
    }

    private function validateText(?string $text): void
    {
        Assert::notEmpty($text, 'Text must not be empty');
        Assert::minLength($text, 3, 'Text must contain at least %2$s characters');
    }

    private function validateChoices(array $choices): void
    {
        Assert::count($choices, 3, 'Choices must containt %s items');
    }
}
