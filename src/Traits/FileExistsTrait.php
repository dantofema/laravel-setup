<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Services\GenerateService;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait FileExistsTrait
{
    /**
     * @throws Exception
     */
    protected function exists (): bool
    {
        foreach ($this->types as $type)
        {
            if ($type == 'migration')
            {
                $this->migrationFileExists();
            }

            $generateService = new GenerateService();

            if (File::exists($generateService->getPath($this->config, $type)))
            {
                $this->error('The ' . $type . ' file "'
                    . $generateService->getName($this->config, $type, true)
                    . '" already exists ');

                throw new Exception('Livewire file exists');
            }
        }

        return false;
    }

    protected function migrationFileExists (): bool
    {
        return collect(File::files('database/migrations/'))
            ->contains(function ($file) {
                $name = $this->config['table']['name'];

                if (Str::contains($file, '_create_' . $name . '_table.php'))
                {
                    throw new Exception('Migration file exists');
                }
            });
    }

    /**
     * @throws Exception
     */
    protected function configFileExists (): bool
    {
        if (File::exists($this->argument('path')))
        {
            return true;
        }
        $this->error('Not found "' . $this->argument('path') . '"');

        throw new Exception('Not found "' . $this->argument('path') . '"');
    }
}
