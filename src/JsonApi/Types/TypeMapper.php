<?php

namespace Extiverse\Api\JsonApi\Types;

use Swis\JsonApi\Client\Interfaces\ItemInterface;
use Swis\JsonApi\Client\TypeMapper as Mapper;

class TypeMapper extends Mapper
{
    protected array $types = [
        Extension\Extension::class,
        User\User::class,
    ];

    public function __construct()
    {
        foreach($this->types as $type) {
            /** @var ItemInterface $type */
            $type = new $type;
            $this->setMapping($type->getType(), get_class($type));
        }
    }
}
