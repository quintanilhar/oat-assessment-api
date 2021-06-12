<?php

declare(strict_types=1);

namespace Quintanilhar\AssessmentApi\Common\Presentation\Web\Responder;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use RuntimeException;
use Symfony\Component\Serializer\Serializer;

final class Responder
{
    private const DEFAULT_CONTENT_TYPE = 'application/json';

    private Serializer $serializer;

    private array $headerToFormatMap;

    public function __construct(Serializer $serializer, array $headerToFormatMap)
    {
        $this->serializer        = $serializer;
        $this->headerToFormatMap = $headerToFormatMap;
    }

    public function respond(Request $request, Response $response, array $payload): Response
    {
        $body = $response->getBody();

        try {
            $body->write(
                $this->serialize($request, $payload)
            );

            return $response->withBody($body)
                ->withHeader('Content-Type', $this->getContentType($request));
        } catch (RuntimeException $exception) {
            return $response->withStatus(StatusCodeInterface::STATUS_NOT_ACCEPTABLE);
        }
    }

    private function serialize(Request $request, array $data): string
    {
        $contentType = $this->getContentType($request);

        return (string)$this->serializer->serialize($data, $this->getFormat($contentType));
    }

    private function getFormat(string $acceptHeader): string
    {
        if (!isset($this->headerToFormatMap[$acceptHeader])) {
            throw new RuntimeException(
                sprintf('No serializer defined for content type "%s"', $acceptHeader)
            );
        }

        return $this->headerToFormatMap[$acceptHeader];
    }

    private function getContentType(Request $request): string
    {
        $contentType = $request->getHeader('Accept')[0] ?? null;

        if (
            empty($contentType)
            || $contentType === '*/*'
        ) {
            $contentType = $request->getHeader('Content-Type')[0] ?? self::DEFAULT_CONTENT_TYPE;

            $contentType = empty($contentType) ? self::DEFAULT_CONTENT_TYPE : $contentType;
        }

        return $contentType;
    }
}
