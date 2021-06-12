<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Quintanilhar\AssessmentApi\Assessment\Application\ListQuestions\ListQuestionsRepository;
use Quintanilhar\AssessmentApi\Assessment\Domain\QuestionRepository;
use Quintanilhar\AssessmentApi\Assessment\Infrastructure\Repository\JsonQuestionRespository;

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
    ]);
};