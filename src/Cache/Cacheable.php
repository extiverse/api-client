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

    public function storeType(string $type, $value)
    {
        /** @var Collection $collection */
        $collection = $this->getCache()->get($type, Collection::forType($type));

        if ($value instanceof Collection) {
            $this->getCache()->set($type, $collection = $collection->merge($value));
        }

        if ($value instanceof Item) {
            $collection->put($value->id, $value);
        }

        $this->getCache()->set($type, $collection);
    }
}