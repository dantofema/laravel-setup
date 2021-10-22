<?php

namespace Dantofema\LaravelSetup\Traits;

use Exception;
use Illuminate\Support\Facades\File;

trait CommandTrait
{
    use FileExistsTrait;

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

        $this->force();

        $this->exists($type);

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

    protected function force (): void
    {
        if ($this->option('force'))
        {
            File::delete(self::DIRECTORY . $this->getFileName());
        }
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