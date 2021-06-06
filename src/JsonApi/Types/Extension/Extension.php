<?php

namespace Extiverse\Api\JsonApi\Types\Extension;

use Swis\JsonApi\Client\Item;

class Extension extends Item
{
    protected $type = 'extensions';
    protected ?string $flarumId = null;


    public function flarumId(): string
    {
        if (! $this->flarumId) {
            [$vendor, $package] = explode('/', $this->name);
            $package = str_replace(['flarum-ext-', 'flarum-'], '', $package);

            $this->flarumId = "$vendor-$package";
        }

        return $this->flarumId;
    }
}
