<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeleteService
{

    public function execute (array $config, array $types): void
    {
        foreach ($types as $type)
        {
            if ($type === 'migration')
            {
                $this->deleteMigrationFile($config['table']['name']);
            }

            if (str_contains($type, 'livewire'))
            {
                (new RouteService())->delete($config);
            }

            File::delete((new GenerateService())->getPath($config, $type));
        }
    }

    protected function deleteMigrationFile (string $tableName): void
    {
        collect(File::files('database/migrations/'))
            ->contains(function ($file) use ($tableName) {
                if (Str::contains($file, '_create_' . $tableName . '_table.php'))
                {
                    File::delete($file);
                }

                if (Str::contains($file, Str::singular($tableName)) and Str::contains($file, 'pivot'))
                {
                    File::delete($file);
                }
            });
    }

}
