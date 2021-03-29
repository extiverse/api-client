<?php

namespace Extiverse\Api\Requests;

use Extiverse\Api\Extiverse;
use Extiverse\Api\JsonApi\Collection;
use GuzzleHttp\Client;

class User
{
    protected ?Client $client;

    public function __construct(string $userToken = null)
    {
        $this->client = Extiverse::instance()->getClient($userToken);
    }

    public function subscriptions(array $query = []): Collection
    {
        $response = $this->client->get('subscriptions', compact('query'));

        return $response->getAttribute('collection');
    }

    public function me()
    {
        $response = $this->client->get('users/me');

        return $response->getAttribute('item');
    }
}