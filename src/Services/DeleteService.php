<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DeleteService
{
    private string $type;

    public function config (array $config): void
    {
        if ($this->type == 'migration')
        {
            $this->deleteMigrationFile($config['table']['name']);
        }

        if ($this->type == 'livewire')
        {
            $this->deleteRoute(Text::config($config)->name('livewire'));
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
            });
    }

    private function deleteRoute (string $livewire)
    {
        $rows = explode(';', File::get(Text::path('route')));

        foreach ($rows as $key => $row)
        {
            if (str_contains($row, $livewire))
            {
                unset($rows[$key]);
            }
        }

        $content = implode(';', $rows);
        File::put(Text::path('route'), $content);
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