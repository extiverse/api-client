<?php

namespace Extiverse\Api\Cache;

use Extiverse\Api\Extiverse;
use Extiverse\Api\JsonApi\Collection;
use Extiverse\Api\JsonApi\Item;
use Psr\SimpleCache\CacheInterface;

trait Cacheable
{
    public function cache(CacheInterface $cache): CacheInterface
    {
        return Extiverse::instance()->getCache();
    }

    public function storeType(string $type, $value)
    {
        /** @var Collection $collection */
        $collection = $this->cache()->get($type, Collection::forType($type));

        if ($value instanceof Collection) {
            $this->cache()->set($type, $collection = $collection->merge($value));
        }

        if ($value instanceof Item) {
            $collection->put($value->id, $value);
        }

        $this->cache()->set($type, $collection);
    }
}