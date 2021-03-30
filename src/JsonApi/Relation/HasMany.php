<?php

namespace Extiverse\Api\JsonApi\Relation;

use Extiverse\Api\JsonApi\Collection;

class HasMany extends Relation
{
    public $ids = [];

    public function __construct(string $type, $ids = [])
    {
        $this->type = $type;
        $this->ids = $ids;
    }

    public function get(): Collection
    {
        return $this->typeCollection()->only($this->ids);
    }
}