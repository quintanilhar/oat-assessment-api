<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\ListQuestionsRepository;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionRepository;
use Quintanilhar\AssessmentApi\Assessment\Infrastructure\Repository\JsonQuestionRespository;
use Quintanilhar\AssessmentApi\Common\Application\TranslateSentenceService;
use Quintanilhar\AssessmentApi\Common\Infrastructure\GoogleTranslateSentenceService;
use Quintanilhar\AssessmentApi\Common\Infrastructure\LanguageRegistry;
use Quintanilhar\AssessmentApi\Common\Presentation\Web\Responder\Responder;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

return function (ContainerBuilder $containerBuilder): void 
{
    $containerBuilder->addDefinitions([
        ListQuestionsRepository::class => function (ContainerInterface $c) {
            return $c->get(JsonQuestionRespository::class);
        },
        QuestionRepository::class => function (ContainerInterface $c) {
            return $c->get(JsonQuestionRespository::class);
        },
        JsonQuestionRespository::class => function (ContainerInterface $c) {
            return new JsonQuestionRespository($c->get('database.path'));
        },
        TranslateSentenceService::class => function (ContainerInterface $c) {
            $translator = new GoogleTranslate(
                $c->get(LanguageRegistry::class)->get(),
                $c->get('language')
            );

            return new GoogleTranslateSentenceService($translator);
        },
        LanguageRegistry::class => function (ContainerInterface $c) {
            return new LanguageRegistry($c->get('language'));
        },
        Responder::class => function (ContainerInterface $c) {
            $serializer = new Serializer(
                [],
                [new CsvEncoder(), new JsonEncoder()]
            );

            return new Responder(
                $serializer,
                [
                    'text/csv'         => 'csv',
                    'application/json' => 'json',
                ]
            );
        },
    ]);
};