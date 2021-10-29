<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Delete;
use Dantofema\LaravelSetup\Facades\Route;
use Illuminate\Console\Command;

class DeleteCommand extends Command
{
    public $signature = 'generate:delete 
                        {path : path to the config file}
                        {--all}
                        {--factory}
                        {--migration}
                        {--model}
                        {--seeder}
                        {--test}
                        {--view}';

    public $description = 'Delete files generated';
    private array $config;

    public function handle (): bool
    {
        $this->config = include $this->argument('path');

        if ($this->option('all'))
        {
            $this->factory();
            $this->livewire();
            $this->migration();
            $this->model();
            $this->test();
            $this->view();
            $this->route();
            $this->seeder();
        }
        return true;
    }

    private function factory ()
    {
        Delete::type('factory')->config($this->config);
    }

    private function livewire ()
    {
        Delete::type('livewire')->config($this->config);
    }

    private function migration ()
    {
        Delete::type('migration')->config($this->config);
    }

    private function model ()
    {
        Delete::type('model')->config($this->config);
    }

    private function test ()
    {
        Delete::type('test')->config($this->config);
    }

    private function view ()
    {
        Delete::type('view')->config($this->config);
    }

    private function route ()
    {
        Route::delete($this->config);
    }

    private function seeder ()
    {
    }

}

