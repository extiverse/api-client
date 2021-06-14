<?php

namespace Extiverse\Tests;

use Extiverse\Api\Extiverse;
use Extiverse\Api\JsonApi\Repositories\Repository;
use PHPUnit\Framework\TestCase;

abstract class Test extends TestCase
{
    protected ?string $repository = null;

    protected function getRepository(): ?Repository
    {
        return $this->repository
            ? new $this->repository()
            : null;
    }
}