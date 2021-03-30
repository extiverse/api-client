<?php

namespace Extiverse\Tests\Requests;

use Extiverse\Api\JsonApi\Collection;
use Extiverse\Api\JsonApi\Item;
use Extiverse\Api\Requests\User;
use Extiverse\Tests\Test;

class UserTest extends Test
{
    /**
     * @test
     * @covers \Extiverse\Api\Requests\User::subscriptions
     */
    function subscriptions()
    {
        $collection = (new User(env('USER_TOKEN')))->subscriptions(['include' => 'extension,plan']);

        $this->assertTrue($collection instanceof Collection);

        $subscription = $collection->first();

        $this->assertTrue($subscription instanceof Item);
        $this->assertTrue($subscription->plan instanceof Item);
        $this->assertTrue($subscription->plan->subscribed);
    }

    /**
     * @test
     * @covers \Extiverse\Api\Requests\User::me
     */
    function me()
    {
        $item = (new User(env('USER_TOKEN')))->me();

        $this->assertTrue($item instanceof Item);
        $this->assertNotEmpty($item->id);
    }
}