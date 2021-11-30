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
use Dantofema\LaravelSetup\Services\FieldService;
use Dantofema\LaravelSetup\Services\FileService;
use Dantofema\LaravelSetup\Services\GenerateService;
use Dantofema\LaravelSetup\Services\ReplaceService;
use Dantofema\LaravelSetup\Services\TextService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSetupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
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
        $this->app->bind('generate', function () {
            return new GenerateService();
        });

        $this->app->bind('file', function () {
            return new FileService();
        });

        $this->app->bind('text', function () {
            return new TextService();
        });

        $this->app->bind('field', function () {
            return new FieldService();
        });

        $this->app->bind('replace', function () {
            return new ReplaceService();
        });
    }

}

