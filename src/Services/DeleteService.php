<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\Pure;

class DeleteService
{
    private string $type;
    private RouteService $route;

    #[Pure] public function __construct ()
    {
        $this->route = new RouteService();
    }

    public function config (array $config): void
    {
        if ($this->type == 'migration')
        {
            $this->deleteMigrationFile($config['table']['name']);
        }

        if ($this->type == 'livewire')
        {
            $this->route->delete($config);
        }

        $this->deleteFile($config);
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

    protected function deleteFile (array $config): void
    {
        File::delete(Text::config($config)->path($this->type));
    }

    public function type (string $type): DeleteService
    {
        $this->type = $type;
        return $this;
    }

}