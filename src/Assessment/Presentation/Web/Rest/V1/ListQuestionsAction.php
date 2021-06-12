<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\ListQuestionsRepository;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\QuestionForList;
use Quintanilhar\AssessmentApi\Common\Application\TranslateSentenceService;

class ListQuestionsAction
{
    private ListQuestionsRepository $listQuestionsRepository;

    private TranslateSentenceService $translateSentenceService;

    public function __construct(
        ListQuestionsRepository $listQuestionsRepository, 
        TranslateSentenceService $translateSentenceService
    ) {
        $this->listQuestionsRepository  = $listQuestionsRepository;
        $this->translateSentenceService = $translateSentenceService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $questions = $this->listQuestionsRepository->all();
        
        $body = $response->getBody();

        $body->write(json_encode(
            array_map(
                function (QuestionForList $question) {
                    return [
                        'text'      => $this->translateSentenceService->__invoke($question->text()),
                        'createdAt' => $question->createdAt(),
                        'choices'   => array_map(fn (string $choice) => $this->translateSentenceService->__invoke($choice), $question->choices())
                    ];
                },
                $questions
            )
        ));

        return $response
            ->withBody($body)
            ->withHeader('Content-Type', 'application/json');
    }
}
