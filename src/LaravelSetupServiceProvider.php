<?php

namespace Dantofema\LaravelSetup;

use Dantofema\LaravelSetup\Commands\DeleteCommand;
use Dantofema\LaravelSetup\Commands\GenerateCrudCommand;
use Dantofema\LaravelSetup\Commands\GenerateFactoryCommand;
use Dantofema\LaravelSetup\Commands\GenerateLivewireCommand;
use Dantofema\LaravelSetup\Commands\GenerateMigrationCommand;
use Dantofema\LaravelSetup\Commands\GenerateModelCommand;
use Dantofema\LaravelSetup\Commands\GenerateTestCommand;
use Dantofema\LaravelSetup\Commands\GenerateViewCommand;
use Dantofema\LaravelSetup\Services\DeleteService;
use Dantofema\LaravelSetup\Services\RouteService;
use Dantofema\LaravelSetup\Services\SeederService;
use Dantofema\LaravelSetup\Services\TextService;
use Dantofema\LaravelSetup\Services\TraitService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSetupServiceProvider extends PackageServiceProvider
{
    public function configurePackage (Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-setup')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-setup_table')
            ->hasCommand(GenerateCrudCommand::class)
            ->hasCommand(DeleteCommand::class)
            ->hasCommand(GenerateMigrationCommand::class)
            ->hasCommand(GenerateModelCommand::class)
            ->hasCommand(GenerateViewCommand::class)
            ->hasCommand(GenerateLivewireCommand::class)
            ->hasCommand(GenerateTestCommand::class)
            ->hasCommand(GenerateFactoryCommand::class);
    }

    public function packageRegistered ()
    {
        $this->app->bind('delete', function () {
            return new DeleteService();
        });

        $this->app->bind('route', function () {
            return new RouteService();
        });

        $this->app->bind('seeder', function () {
            return new SeederService();
        });

        $this->app->bind('traits', function () {
            return new TraitService();
        });

        $this->app->bind('text', function () {
            return new TextService();
        });
    }

}

