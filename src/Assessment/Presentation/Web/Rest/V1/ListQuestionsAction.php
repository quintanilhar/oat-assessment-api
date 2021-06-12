<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\ListQuestionsRepository;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\QuestionForList;
use Quintanilhar\AssessmentApi\Common\Application\TranslateSentenceService;
use Quintanilhar\AssessmentApi\Common\Presentation\Web\Responder\Responder;

class ListQuestionsAction
{
    private ListQuestionsRepository $listQuestionsRepository;

    private TranslateSentenceService $translateSentenceService;

    private Responder $responder;

    public function __construct(
        ListQuestionsRepository $listQuestionsRepository, 
        TranslateSentenceService $translateSentenceService,
        Responder $responder
    ) {
        $this->listQuestionsRepository  = $listQuestionsRepository;
        $this->translateSentenceService = $translateSentenceService;
        $this->responder                = $responder;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $queryParams = $request->getQueryParams();

        if (empty($queryParams['lang'])) {
            return $this->responder->respond($request, $response, ['lang' => 'Lang must be provided']);
        }

        $translate = $this->translateSentenceService;
        $questions = $this->listQuestionsRepository->all();
        
        return $this->responder->respond(
            $request, 
            $response,
            array_map(
                function (QuestionForList $question) use ($translate) {
                    return [
                        'text'      => $translate($question->text()),
                        'createdAt' => $question->createdAt(),
                        'choices'   => array_map(fn (string $choice) => $translate($choice), $question->choices())
                    ];
                },
                $questions
            )
        );
    }
}
