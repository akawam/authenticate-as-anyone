<?php

namespace Leekman\AuthenticateAsAnyone\Tests;

use Leekman\AuthenticateAsAnyone\AuthenticateAsAnyoneServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app): array
    {
        return [
            AuthenticateAsAnyoneServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
