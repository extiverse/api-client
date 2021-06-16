<?php

namespace Extiverse\Api\JsonApi\Repositories;

use Extiverse\Api\JsonApi\Types\User\User;

class UserRepository extends Repository
{
    protected $endpoint = 'users';

    public function me(array $parameters = []): ?User
    {
        $response = $this->getClient()->get($this->getEndpoint().'/me?'.http_build_query($parameters));

        return $response->getData();
    }
}
