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

    public function cache(): self
    {
        $this->storeType($this->type, $this);

        return $this;
    }

    public function typeCollection(): Collection
    {
        return $this->getCache()->get($this->type, Collection::forType($this->type));
    }

    public function storeType(string $type, $value)
    {
        $collection = $this->typeCollection();

        if ($value instanceof Collection) {
            $collection = $collection->merge($value);
        }

        if ($value instanceof Item) {
            $collection->put($value->id, $value);
        }

        $this->getCache()->set($collection->type, $collection);
    }
}