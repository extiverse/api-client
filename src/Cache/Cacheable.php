<?php

namespace Extiverse\Api\Cache;

use Extiverse\Api\Extiverse;
use Extiverse\Api\JsonApi\Collection;
use Extiverse\Api\JsonApi\Item;
use Psr\SimpleCache\CacheInterface;

trait Cacheable
{
    public function getCache(): CacheInterface
    {
        return Extiverse::instance()->getCache();
    }

    public function typeCollection(): Collection
    {
        return $this->getCache()->get($this->type, Collection::forType($this->type));
    }

    public function cache(): self
    {
        $collection = $this->typeCollection();

        if ($this instanceof Collection) {
            $collection = $collection->merge($this);
        }

        if ($this instanceof Item) {
            $collection->put($this->id, $this);
        }

        $this->getCache()->set($collection->getType(), $collection);

        return $this;
    }
}