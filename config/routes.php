<?php

declare(strict_types=1);

use Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1\CreateQuestionAction;
use Quintanilhar\AssessmentApi\Assessment\Presentation\Web\Rest\V1\ListQuestionsAction;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app): void
{
    $app->group('/v1', function (Group $group) {
        $group->post('/questions', CreateQuestionAction::class);
        $group->get('/questions', ListQuestionsAction::class);
    });
};