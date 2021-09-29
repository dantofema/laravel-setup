<?php

namespace Dantofema\LaravelSetup\Commands;

use Exception;
use Illuminate\Support\Facades\File;
use Str;

trait CommandTrait
{
    protected array $config;
    private string $stub;

    protected function getModelPath (): string
    {
        return $this->config['model']['namespace'] . '\\' . $this->config['model']['name'];
    }

    protected function inArray (string $needle, array $array): bool
    {
        return in_array($needle, call_user_func_array('array_merge', $array));
    }

    /**
     * @throws Exception
     */
    protected function init (?string $type = null): bool
    {
        if ( ! $this->configFileExists())
        {
            throw new Exception('Config file not found');
        };

        $this->config = $this->getConfig();

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

        return $this->setStub();
    }

    protected function configFileExists (): bool
    {
        if (File::exists($this->argument('path')))
        {
            return true;
        }
        $this->error('Not found "' . $this->argument('path') . '"');
        $this->error('Exit');
        return false;
    }

    protected function getConfig (): mixed
    {
        return include $this->argument('path');
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

    protected function setStub (): bool
    {
        $content = file_get_contents(__DIR__ . self::STUB_PATH);

        if ($content)
        {
            $this->stub = $content;
            return true;
        }

        $this->error('Error get stub');
        $this->error('Exit');
        return false;
    }

    protected function getVariableModel (): string
    {
        return '$' . strtolower($this->getModelName());
    }

    protected function getModelName (): string
    {
        return $this->config['model']['name'];
    }

}