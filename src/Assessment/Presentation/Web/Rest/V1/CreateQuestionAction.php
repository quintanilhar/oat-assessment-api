<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1;

use DateTimeImmutable;
use DateTimeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion\CreateQuestionCommand;
use Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion\CreateQuestionService;

class CreateQuestionAction
{
    private CreateQuestionService $createQuestionService;

    public function __construct(CreateQuestionService $createQuestionService)
    {
        $this->createQuestionService = $createQuestionService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getParsedBody();

        $command = new CreateQuestionCommand(
            $params['text'],
            new DateTimeImmutable(),
            $params['choices']
        );

        $this->createQuestionService->__invoke($command);

        $body = $response->getBody();

        $body->write(json_encode([
            'text'      => $command->text(),
            'createdAt' => $command->createdAt()->format(DateTimeInterface::RFC3339),
            'choices'   => $command->choices()
        ]));

        return $response
            ->withBody($body)
            ->withHeader('Content-Type', 'application/json');
    }
}
