<?php

namespace Extiverse\Api\Requests;

use Extiverse\Api\Extiverse;
use GuzzleHttp\Client;

class User
{
    protected ?Client $client;

    public function __construct(string $userToken = null)
    {
        $this->client = Extiverse::instance()->getClient($userToken);
    }

    public function subscriptions(array $get)
    {
        $response = $this->client->get('subscriptions');

        return $response->getAttribute('collection');
    }
}