<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Console\Command;

class GenerateCrudCommand extends Command
{
    public $signature = 'generate:crud 
                        {path : path to the config file }
                        {--force}
                        {--all}
                        {--factory}
                        {--migration}
                        {--model}
                        {--seeder}
                        {--test}
                        {--view}';

    public $description = 'CRUD generator';

    public function handle (): bool
    {
        if ($this->option('all'))
        {
            $this->factory();
            $this->livewire();
            $this->migration();
            $this->model();
            $this->seeder();
            $this->test();
            $this->view();
        }
        return true;
    }

    protected function factory (): void
    {
        $this->call('generate:factory', [
                'path' => $this->argument('path'),
                '--force' => $this->option('force')]
        );
    }

    protected function livewire (): void
    {
        $this->call('generate:livewire', [
                'path' => $this->argument('path'),
                '--force' => $this->option('force')]
        );
    }

    protected function migration (): void
    {
        $this->call('generate:migration', [
                'path' => $this->argument('path'),
                '--force' => $this->option('force')]
        );
    }

    protected function model (): void
    {
        $this->call('generate:GenerateCrudCommand', [
                'path' => $this->argument('path'),
                '--force' => $this->option('force')]
        );
    }

    protected function seeder (): void
    {
        $this->call('generate:seeder', [
                'path' => $this->argument('path'),
                '--force' => $this->option('force')]
        );
    }

    protected function test (): void
    {
        $this->call('generate:test', [
                'path' => $this->argument('path'),
                '--force' => $this->option('force')]
        );
    }

    protected function view (): void
    {
        $this->call('generate:view', [
                'path' => $this->argument('path'),
                '--force' => $this->option('force')]
        );
    }
}

