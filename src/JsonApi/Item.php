<?php

namespace Extiverse\Api\JsonApi;

use Extiverse\Api\Cache\Cacheable;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class Item extends Fluent
{
    use Cacheable;

    public $id = null;
    public ?string $type = null;

    public static function fromResponse(array $data): Item
    {
        $item = new Item(Arr::get($data, 'attributes', []));
        $item->id = Arr::get($data, 'id');
        $item->type = Arr::get($data, 'type');

        $item->cache();

        return $item;
    }
}