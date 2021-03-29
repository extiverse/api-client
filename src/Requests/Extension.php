<?php

namespace Extiverse\Api\Requests;

use Extiverse\Api\Extiverse;
use GuzzleHttp\Client;

class Extension
{
    protected ?Client $client;

    public function __construct(string $userToken = null)
    {
        $this->client = Extiverse::instance()->getClient($userToken);
    }
    public function get(string $name)
    {
        $response = $this->client->get('extension/' . $name);

        return $response->getAttribute('item');
    }
}