<?php

namespace Dantofema\LaravelSetup\Commands;

use Illuminate\Support\Facades\File;
use Str;

trait CommandTrait
{
    protected array $config;
    private string $stub;

    public function getModelPath (): string
    {
        return $this->config['model']['namespace'] . '\\' . $this->config['model']['name'];
    }

    protected function getModelName (): string
    {
        return $this->config['model']['name'];
    }

    protected function inArray (string $needle, array $array): bool
    {
        return in_array($needle, call_user_func_array('array_merge', $array));
    }

    protected function init (?string $type = null): bool
    {
        if ( ! $this->configFileExists())
        {
            return false;
        };

        $this->config = $this->getConfig();

        if ($type == 'migration' and $this->migrationFileExists())
        {
            return false;
        }

        if ($this->fileExists())
        {
            return false;
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

    public function migrationFileExists (): bool
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

    protected function fileExists (): bool
    {
        if (File::exists(self::DIRECTORY . $this->getFileName()))
        {
            $this->error('The migration file "' . $this->getFileName() . '" already exists ');
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

}