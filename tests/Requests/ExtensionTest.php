<?php

namespace Extiverse\Tests\Requests;

use Extiverse\Api\Requests\Extension;
use Extiverse\Tests\Test;

class ExtensionTest extends Test
{
    /** @test */
    function reads_subscriptions()
    {
        (new Extension(env('USER_TOKEN')))->get('flarum/tags');
    }
}