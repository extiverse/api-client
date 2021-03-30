<?php

namespace Extiverse\Api\JsonApi\Relation;

use Extiverse\Api\Cache\Cacheable;

abstract class Relation
{
    use Cacheable;

    public string $type;

    abstract public function get();
}