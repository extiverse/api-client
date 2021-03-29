<?php

namespace Extiverse\Tests;

use Extiverse\Api\Extiverse;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Boots env()
        Extiverse::instance();
    }
}