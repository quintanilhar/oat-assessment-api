<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1;

use DateTimeImmutable;
use DateTimeInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion\CreateQuestionCommand;
use Quintanilhar\AssessmentApi\Assessment\Application\CreateQuestion\CreateQuestionService;
use Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1\Validator\CreateQuestionRequestValidator;
use Quintanilhar\AssessmentApi\Common\Presentation\Web\Responder\Responder;

class CreateQuestionAction
{
    private CreateQuestionRequestValidator $requestValidator;

    private CreateQuestionService $createQuestionService;

    private Responder $responder;

    public function __construct(
        CreateQuestionRequestValidator $requestValidator, 
        CreateQuestionService $createQuestionService,
        Responder $responder
    ) {
        $this->requestValidator      = $requestValidator;
        $this->createQuestionService = $createQuestionService;
        $this->responder             = $responder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $errors = $this->requestValidator->validate($request);

        if (!empty($errors)) {
            $response = $response->withStatus(StatusCodeInterface::STATUS_BAD_REQUEST);

            return $this->responder->respond($request, $response, $errors);
        }

        $params = $request->getParsedBody();

        $command = new CreateQuestionCommand(
            $params['text'],
            new DateTimeImmutable(),
            $params['choices']
        );

        $this->createQuestionService->__invoke($command);

        $payload = [
            'text'      => $command->text(),
            'createdAt' => $command->createdAt()->format(DateTimeInterface::RFC3339),
            'choices'   => $command->choices()
        ];

        return $this->responder->respond($request, $response, $payload);
    }
}
