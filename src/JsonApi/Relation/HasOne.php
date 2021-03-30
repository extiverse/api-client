<?php

namespace Extiverse\Api\JsonApi\Relation;

class HasOne extends Relation
{
    public $id;

    public function __construct(string $type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    public function get()
    {
        return $this->typeCollection()->get($this->id);
    }
}