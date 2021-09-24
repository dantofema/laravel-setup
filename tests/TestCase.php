<?php

namespace Dantofema\LaravelSetup\Tests;

use Dantofema\LaravelSetup\LaravelSetupServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp ($app)
    {
        config()->set('database.default', 'testing');
        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-setup_table.php.stub';
        $migration->up();
        */
    }

    protected function setUp (): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'Dantofema\\LaravelSetup\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders ($app)
    {
        return [
            LaravelSetupServiceProvider::class,
        ];
    }
}
