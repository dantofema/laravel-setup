<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Console\Command;

class DeleteCommand extends Command
{
    public $signature = 'generate:delete 
                        {path : path to the config file}
                        {type : type of file to delete, if you want to delete all files pass the argument "all" }';

    public $description = 'Delete files generated';
    private array $config;
    private array $types = [
        'factory',
        'livewire',
        'livewireAllInOne',
        'migration',
        'model',
        'test',
        'viewAllInOne',
        'viewModel',
        'viewCollection',
    ];

    public function handle (): bool
    {
        $this->config = include $this->argument('path');

        if ($this->argument('type') === 'route')
        {
            $this->route();

            return true;
        }

        if ($this->argument('type') === 'all')
        {
            gen()->delete($this->config, $this->types);

            $this->route();

            return true;
        }

        gen()->delete($this->config, $this->argument('type'));

        return true;
    }

    private function route ()
    {
        gen()->removeRoute($this->config);
    }
}
