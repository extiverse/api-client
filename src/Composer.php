<?php

namespace Extiverse\Api;

use GuzzleHttp\Client;

class Composer
{
    protected Client $client;

    public function __construct(string $userToken = null)
    {
        $this->client = Extiverse::instance()->generateGuzzleClient($userToken, false);
    }

    public function packages(): array
    {
        $response = $this->client->get('composer/packages.json');

        return json_decode($response->getBody()->getContents(), true);
    }
}
