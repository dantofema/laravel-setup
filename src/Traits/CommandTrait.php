<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Facades\Before;
use Dantofema\LaravelSetup\Facades\Delete;
use Dantofema\LaravelSetup\Facades\Route;
use Dantofema\LaravelSetup\Facades\Text;
use Exception;

trait CommandTrait
{
    use FileExistsTrait;

    protected array $config;
    private string $jetstreamPath = '/../Stubs/view/jetstream/basic.blade.php';
    private string $tailwindPath = '/../Stubs/view/tailwind/basic.blade.php';
    private string $stub;
    private Delete $delete;

    protected function inArray (string $needle, array $array): bool
    {
        return in_array($needle, call_user_func_array('array_merge', $array));
    }

    /**
     * @throws Exception
     */
    protected function init (string $type): bool
    {
        Before::setup();

        $this->configFileExists();

        $this->config = include $this->argument('path');

        if ($this->option('force'))
        {
            Delete::type($type)->config($this->config);
        }

        if ($type == 'livewire')
        {
            Route::add($this->config);
        }

        $this->exists($type);

        $this->getStub($type);

        return true;
    }

    protected function getStub (string $type): void
    {
        if ($type !== 'view')
        {
            $path = self::STUB_PATH;
        } else
        {
            $path = $this->config['view']['jetstream']
                ? $this->jetstreamPath
                : $this->tailwindPath;
        }
        $this->stub = file_get_contents(__DIR__ . $path);
    }

    protected function getVariableModel (): string
    {
        return '$' . strtolower(Text::config($this->config)->name('model'));
    }

}