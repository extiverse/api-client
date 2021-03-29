<?php

namespace Extiverse\Api\Guzzle;

use Extiverse\Api\JsonApi\Collection;
use Extiverse\Api\JsonApi\Item;
use Extiverse\Api\JsonApi\Response as JsonApiResponse;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;

class JsonApiParserMiddleware
{
    public function __invoke(Response $response)
    {
        $response = new JsonApiResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
        );

        if (in_array('application/vnd.api+json', $response->getHeader('Content-Type'))) {
            $body = json_decode($response->getBody()->getContents(), true);

            foreach(Arr::get($body, 'included', []) as $include) {
                Item::fromResponse($include);
            }

            if (Arr::get($body, 'meta.page')) {
                return $response->withAttribute('collection', Collection::fromResponse($body));
            }

            if (Arr::get($body, 'data.type')) {
                return $response->withAttribute('item', Item::fromResponse($body['data']));
            }
        }

        return $response;
    }
}