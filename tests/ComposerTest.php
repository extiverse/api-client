<?php

namespace Extiverse\Tests;

use Extiverse\Api\Composer;

class ComposerTest extends Test
{
    /**
     * @test
     * @covers \Extiverse\Api\Extiverse::generateGuzzleClient
     * @covers \Extiverse\Api\Composer::packages
     */
    function packages()
    {
        $packages = (new Composer)->packages();

        $this->assertIsArray($packages);
    }
}
