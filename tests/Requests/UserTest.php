<?php

namespace Extiverse\Tests;

use Extiverse\Api\Requests\User;
use Extiverse\Api\Tests\Test;

class UserTest extends Test
{
    /** @test */
    function reads_subscriptions()
    {
        (new User())
    }
}