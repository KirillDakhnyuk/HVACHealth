<?php

namespace HvacHealth\Tests;

use HvacHealth\Contracts\PopALockContract;
use HvacHealth\HealthServiceProvider;
use HvacHealth\Tests\Fakes\PopALock;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        app()->bind(PopALockContract::class, PopALock::class);
    }
}
