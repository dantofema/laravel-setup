<?php

namespace Dantofema\LaravelSetup;

use Dantofema\LaravelSetup\Commands\GenerateFactoryCommand;
use Dantofema\LaravelSetup\Commands\GenerateMigrationCommand;
use Dantofema\LaravelSetup\Commands\GenerateModelCommand;
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
            ->hasCommand(GenerateMigrationCommand::class)
            ->hasCommand(GenerateModelCommand::class)
            ->hasCommand(GenerateFactoryCommand::class);
    }
}
