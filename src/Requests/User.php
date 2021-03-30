<?php

namespace Extiverse\Api\Requests;

use Extiverse\Api\Extiverse;
use Extiverse\Api\JsonApi\Collection;
use Extiverse\Api\JsonApi\Item;
use GuzzleHttp\Client;

class User
{
    protected ?Client $client;

    public function __construct(string $userToken = null)
    {
        $this->client = Extiverse::instance()->getClient($userToken);
    }

    public function subscriptions(array $query = []): ?Collection
    {
        return $this->client
            ->get('subscriptions', compact('query'))
            ->getAttribute('collection');
    }

    public function me(): ?Item
    {
        return $this->client
            ->get('users/me')
            ->getAttribute('item');
    }
}