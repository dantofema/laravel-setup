<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Console\Command;

class DeleteCommand extends Command
{
    public $signature = 'generate:delete 
                        {path : path to the config file}
                        {type : type of file to delete, if you want to delete all files pass the argument "all" }
                        ';

    public $description = 'Delete files generated';

    public function handle (): bool
    {
        $config = include $this->argument('path');

        if ($this->argument('type') === 'route')
        {
            $this->route($config);

            return true;
        }

        if ($this->argument('type') === 'all')
        {
            gen()->route()->delete($config);

            $this->route($config);

            return true;
        }

        gen()->delete()->$this->argument('type')($config);

        return true;
    }

    private function route (array $config): void
    {
        gen()->route()->delete($config);
    }
}
