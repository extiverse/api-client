<?php

namespace Extiverse\Api\Guzzle;

use Extiverse\Api\JsonApi\Collection;
use Extiverse\Api\JsonApi\Response as JsonApiResponse;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Arr;

class JsonApiParserMiddleware
{
    public function __invoke(Response $response)
    {
        if (in_array('application/vnd.api+json', $response->getHeader('Content-Type'))) {
            $body = json_decode($response->getBody()->getContents(), true);

            $response = new JsonApiResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $response->getBody(),
            );

            if (Arr::get($body, 'meta.page')) {
                return $response->withAttribute('collection', Collection::fromResponse($body));
            }

            dd($body);
        }

        return $response;
    }
}