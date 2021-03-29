<?php

namespace Extiverse\Api\JsonApi;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class Item extends Fluent
{
    public $id = null;
    public ?string $type = null;

    public static function fromResponse(array $data): Item
    {
        $item = new Item(Arr::get($data, 'attributes', []));
        $item->id = $data['id'];
        $item->type = $data['type'];

        return $item;
    }
}