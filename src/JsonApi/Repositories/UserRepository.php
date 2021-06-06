<?php

namespace Extiverse\Api\JsonApi\Repositories;

class UserRepository extends Repository
{
    protected $endpoint = 'users';

    public function me(array $parameters = [])
    {
        return $this->getClient()->get($this->getEndpoint().'/me?'.http_build_query($parameters));
    }
}
