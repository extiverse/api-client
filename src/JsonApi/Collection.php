<?php

namespace Extiverse\Api\JsonApi;

use Extiverse\Api\Cache\Cacheable;
use Illuminate\Support\Arr;

class Collection extends \Illuminate\Support\Collection
{
    use Cacheable;

    protected array $page = [];
    protected array $links = [];
    protected ?string $type = null;

    public static function forType(string $type): self
    {
        $collection = self::make();
        $collection->type = $type;

        return $collection;
    }

    public static function fromResponse(array $data): self
    {
        $collection = self::make(Arr::get($data, 'data'));

        if (Arr::has($data, 'data.0.id')) {
            $collection = $collection
                ->keyBy('id')
                ->map(function (array $attributes) {
                    return Item::fromResponse($attributes);
                });
        }

        $collection->type = $collection->first()->type ?? null;
        $collection->page = Arr::get($data, 'meta.page');
        $collection->links = Arr::get($data, 'links');

        $collection->cache();

        return $collection;
    }

    public function getType(): ?string
    {
        return $this->type ?? $this->first()->type ?? null;
    }
}