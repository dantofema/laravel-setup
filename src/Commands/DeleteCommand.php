<?php

namespace Dantofema\LaravelSetup\Commands;

use Dantofema\LaravelSetup\Facades\Delete;
use Dantofema\LaravelSetup\Facades\Generate;
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
        'migration',
        'model',
        'test',
        'view',
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
            foreach ($this->types as $type)
            {
                Generate::delete($this->config, $type);
            }
            $this->route();
            return true;
        }

        Generate::delete($this->config, $this->argument('type'));
        return true;
    }

    private function route ()
    {
        Generate::removeRoute($this->config);
    }

}

