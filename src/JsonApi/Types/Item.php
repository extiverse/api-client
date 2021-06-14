<?php

namespace Extiverse\Api\JsonApi\Types;

use Illuminate\Support\Str;

class Item extends \Swis\JsonApi\Client\Item
{
    public function __get($key)
    {
        $key = Str::snake($key, '-');

        return parent::__get($key);
    }

    protected function f($key)
    {
        dump($key);
        return parent::getAttributeFromArray($key)
            ?? parent::getAttributeFromArray(Str::snake($key, '-'));
    }
}