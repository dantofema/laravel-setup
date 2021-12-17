<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Console\Command;

class GenerateCrudCommand extends Command
{
    public $signature = 'generate:crud 
                        {path? : path to the config file }
                        {--force}
                        {--setup}
                        ';

    public $description = 'CRUD generator';

    public function handle (): bool
    {
        if ($this->option('setup'))
        {
            $this->setUp();
            return true;
        }

        $this->factory();
        $this->livewire();
        $this->migration();
        $this->model();
        $this->test();
        $this->view();
        return true;
    }

    protected function setUp ()
    {
        gen()->setup();
    }

    protected function factory (): void
    {
        $this->call(
            'generate:factory',
            [
                'path' => $this->argument('path'),
                '--force' => $this->option('force'),]
        );
    }

    protected function livewire (): void
    {
        $this->call(
            'generate:livewire',
            [
                'path' => $this->argument('path'),
                '--force' => $this->option('force'),]
        );
    }

    protected function migration (): void
    {
        $this->call(
            'generate:migration',
            [
                'path' => $this->argument('path'),
                '--force' => $this->option('force'),]
        );
    }

    protected function model (): void
    {
        $this->call(
            'generate:model',
            [
                'path' => $this->argument('path'),
                '--force' => $this->option('force'),]
        );
    }

    protected function test (): void
    {
        $this->call(
            'generate:test',
            [
                'path' => $this->argument('path'),
                '--force' => $this->option('force'),]
        );
    }

    protected function view (): void
    {
        $this->call(
            'generate:view',
            [
                'path' => $this->argument('path'),
                '--force' => $this->option('force'),]
        );
    }
}
