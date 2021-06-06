<?php

namespace Extiverse\Api\JsonApi\Repositories;

use Extiverse\Api\Extiverse;
use Swis\JsonApi\Client\DocumentFactory;
use Swis\JsonApi\Client\Interfaces\ItemInterface;

abstract class Repository extends \Swis\JsonApi\Client\Repository
{
    public function __construct(string $userToken = null)
    {
        parent::__construct(
            Extiverse::instance()->getClient($userToken),
            new DocumentFactory
        );
    }

    public function save(ItemInterface $item, array $parameters = [])
    {
        // Not implemented.
    }

    protected function saveExisting(ItemInterface $item, array $parameters = [])
    {
        // Not implemented.
    }
    protected function saveNew(ItemInterface $item, array $parameters = [])
    {
        // Not implemented.
    }
    public function delete(string $id, array $parameters = [])
    {
        // Not implemented.
    }
}
