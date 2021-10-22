<?php

namespace Dantofema\LaravelSetup\Traits;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait FileExistsTrait
{
    /**
     * @throws Exception
     */
    protected function exists (?string $type): void
    {
        if ($type == 'migration' and $this->migrationFileExists())
        {
            throw new Exception('Migration file exists');
        }

        if ($type == 'livewire' and $this->livewireFileExists())
        {
            throw new Exception('Livewire file exists');
        }

        if ($this->fileExists())
        {
            throw new Exception('File exists');
        }
    }

    protected function migrationFileExists (): bool
    {
        return collect(File::files('database/migrations/'))
            ->contains(function ($file) {
                $name = $this->config['table']['name'];
                if (Str::contains($file, '_create_' . $name . '_table.php'))
                {
                    return true;
                }
                return false;
            });
    }

    protected function livewireFileExists (): bool
    {
        $backend = self::DIRECTORY . 'Backend/' . $this->getFileName();
        $frontend = self::DIRECTORY . 'Frontend/' . $this->getFileName();
        if (File::exists($backend) or File::exists($frontend))
        {
            $this->error('The livewire file "' . $this->getFileName() . '" already exists ');
            $this->error('Exit');
            return true;
        }
        return false;
    }

    protected function fileExists (): bool
    {
        $path = self::DIRECTORY . $this->getFileName();
        if (File::exists($path))
        {
            $this->error('The  file "' . $this->getFileName() . '" already exists ');
            $this->error('Exit');
            return true;
        }
        return false;
    }
}