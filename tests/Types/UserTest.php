<?php

namespace Extiverse\Tests\Types;

use Extiverse\Api\JsonApi\Repositories\UserRepository;
use Extiverse\Api\JsonApi\Types\User\User;
use Extiverse\Tests\Test;

class UserTest extends Test
{
    protected ?string $repository = UserRepository::class;

    /**
     * @test
     * @covers \Extiverse\Api\JsonApi\Repositories\UserRepository::me
     */
    function me()
    {
        $user = $this->getRepository()->me();

        $this->assertTrue($user instanceof User);
        $this->assertNotNull($user->nickname);
    }
}
