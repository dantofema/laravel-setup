<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Facades\Delete;
use Dantofema\LaravelSetup\Facades\Route;
use Dantofema\LaravelSetup\Facades\Traits;
use Exception;
use Illuminate\Support\Facades\File;

trait CommandTrait
{
    use FileExistsTrait;

    protected array $config;
    private string $stub;
    private Delete $delete;

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
    protected function init (string $type): bool
    {
        File::ensureDirectoryExists(self::DIRECTORY);

        Traits::withSaveNewImage();

        $this->configFileExists();

        $this->config = $this->getConfig();

        if ($this->option('force'))
        {
            Delete::type($type)->config($this->config);
        }

        if ($type == 'livewire')
        {
            Route::add($this->config);
        }

        $this->exists($type);

        return $this->setStub();
    }

    protected function getConfig (): mixed
    {
        return include $this->argument('path');
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