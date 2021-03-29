<?php

namespace Extiverse\Api\Requests;

use Extiverse\Api\Extiverse;
use Extiverse\Api\JsonApi\Collection;
use Extiverse\Api\JsonApi\Item;
use GuzzleHttp\Client;

class Extension
{
    protected ?Client $client;

    public function __construct(string $userToken = null)
    {
        $this->client = Extiverse::instance()->getClient($userToken);
    }

    public function index(array $query = []): ?Collection
    {
        $response = $this->client->get('extensions', compact('query'));

        return $response->getAttribute('collection');
    }

    public function get(string $name, array $query = []): ?Item
    {
        $response = $this->client->get('extensions/' . str_replace('/', '$', $name), compact('query'));

        return $response->getAttribute('item');
    }
}