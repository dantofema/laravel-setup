<?php

namespace Dantofema\LaravelSetup\Tests;

use Dantofema\LaravelSetup\LaravelSetupServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
<<<<<<< HEAD

    public function getEnvironmentSetUp ($app)
=======
    public function getEnvironmentSetUp($app)
>>>>>>> 0fcc104187b2328a8856ac256be39a8f89dc7392
    {
        config()->set('database.default', 'testing');
        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-setup_table.php.stub';
        $migration->up();
        */
    }

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Dantofema\\LaravelSetup\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelSetupServiceProvider::class,
        ];
    }
}
