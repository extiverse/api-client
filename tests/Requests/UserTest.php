<?php

namespace Extiverse\Tests\Requests;

use Extiverse\Api\Requests\User;
use Extiverse\Tests\Test;

class UserTest extends Test
{
    /** @test */
    function reads_subscriptions()
    {
        (new User(env('USER_TOKEN')))->subscriptions();
    }
}