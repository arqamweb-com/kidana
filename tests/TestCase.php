<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\URL;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $this->forceTestingConfigCachePath();

        parent::setUp();

        $this->guardAgainstUnsafeTestingDatabase();

        URL::defaults(['locale' => 'en']);
    }

    private function forceTestingConfigCachePath(): void
    {
        $testingConfigCachePath = dirname(__DIR__).'/bootstrap/cache/testing-config.php';

        putenv('APP_CONFIG_CACHE='.$testingConfigCachePath);
        $_ENV['APP_CONFIG_CACHE'] = $testingConfigCachePath;
        $_SERVER['APP_CONFIG_CACHE'] = $testingConfigCachePath;
    }

    private function guardAgainstUnsafeTestingDatabase(): void
    {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        if (! app()->environment('testing') || $connection !== 'sqlite' || $database !== ':memory:') {
            throw new \RuntimeException(
                sprintf(
                    'Unsafe test database configuration detected. Refusing to run tests with APP_ENV=%s, DB_CONNECTION=%s, DB_DATABASE=%s.',
                    app()->environment(),
                    $connection,
                    is_scalar($database) ? (string) $database : gettype($database),
                ),
            );
        }
    }
}
