<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Common\Presentation\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Quintanilhar\AssessmentApi\Common\Infrastructure\LanguageRegistry;

class RegisterLanguageMiddleware implements Middleware
{
    private LanguageRegistry $languageRegistry;

    public function __construct(LanguageRegistry $languageRegistry)
    {
        $this->languageRegistry = $languageRegistry;
    }
    
    public function process(Request $request, RequestHandler $handler): Response
    {
        $queryParams = $request->getQueryParams();

        if (!empty($queryParams['lang'])) {
            $this->languageRegistry->set($queryParams['lang']);
        }

        return $handler->handle($request);
    }
}
