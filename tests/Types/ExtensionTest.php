<?php

namespace Extiverse\Tests\Types;

use Extiverse\Api\JsonApi\Repositories\ExtensionRepository;
use Extiverse\Api\JsonApi\Types\Extension\Extension;
use Extiverse\Tests\Test;
use Swis\JsonApi\Client\Collection;

class ExtensionTest extends Test
{
    protected ?string $repository = ExtensionRepository::class;

    /**
     * @test
     * @covers \Extiverse\Api\JsonApi\Types\Extension\Extension::flarumId
     */
    function flarumId()
    {
        /** @var Extension $extension */
        $extension = $this->getRepository()->find('flarum$approval')->getData();

        $this->assertEquals('flarum-approval', $extension->flarumId());
    }

    /**
     * @test
     * @covers \Extiverse\Api\JsonApi\Repositories\ExtensionRepository::find
     */
    function find()
    {
        $response = $this->getRepository()->find('flarum$approval');

        $this->assertFalse($response->hasErrors());
        $this->assertTrue($response->getData() instanceof Extension);
    }

    /**
     * @test
     * @covers \Extiverse\Api\JsonApi\Repositories\ExtensionRepository::all
     */
    function all()
    {
        $response = $this->getRepository()->all();

        $this->assertFalse($response->hasErrors());
        $this->assertTrue($response->getData() instanceof Collection);
    }
}