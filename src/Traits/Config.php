<?php

namespace Dantofema\LaravelSetup\Traits;

use Illuminate\Support\Facades\File;

trait Config
{
    protected function configFileExists(): bool
    {
        if (File::exists($this->argument('path'))) {
            return true;
        }
        $this->error('Not found "' . $this->argument('path') . '"');
        $this->error('Exit');

        return false;
    }

    protected function getConfig(): mixed
    {
        return include $this->argument('path');
    }

    protected function getModelName(): string
    {
        $array = explode('\\', $this->config['model']);

        return end($array);
    }

    protected function inArray(string $needle, array $columns): bool
    {
        return in_array($needle, call_user_func_array('array_merge', $columns));
    }

    protected function getStub(): string|false
    {
        return file_get_contents(__DIR__ . self::STUB_PATH);
    }

    protected function fileExists(): bool
    {
        if (File::exists(self::DIRECTORY . $this->getFileName())) {
            $this->error('The migration file "' . $this->getFileName() . '" already exists ');
            $this->error('Exit');

            return true;
        }

        return false;
    }
}
