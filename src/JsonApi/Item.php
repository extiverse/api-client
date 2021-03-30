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
    public array $relations = [];

    public static function fromResponse(array $data): Item
    {
        $item = new Item(Arr::get($data, 'attributes', []));
        $item->id = Arr::get($data, 'id');
        $item->type = Arr::get($data, 'type');

        foreach (Arr::get($data, 'relationships', []) as $relation => $value) {
            if ($type = Arr::get($value, 'data.type')) {
                $item->relations[$relation] = new Relation\HasOne($type, Arr::get($value, 'data.id'));
            }

            if ($type = Arr::get($value, "data.0.type")) {
                $item->relations[$relation] = new Relation\HasMany($type, Arr::pluck($value, 'data.id'));
            }
        }

        $item->cache();

        return $item;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key]->get();
        }

        return parent::__get($key);
    }
}