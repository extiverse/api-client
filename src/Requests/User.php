<?php

namespace Extiverse\Api\Requests;

use Extiverse\Api\Extiverse;
use GuzzleHttp\Client;

class User
{
    protected ?string $userToken;
    protected ?Client $client;

    public function __invoke(string $userToken): self
    {
        $this->userToken = $userToken;

        $this->client = Extiverse::instance()->getClient($userToken);

        return $this;
    }

    public function subscriptions()
    {
        $response = $this->client->get('subscriptions');
        dd($response);
    }
}