<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Facades\Before;
use Dantofema\LaravelSetup\Facades\Delete;
use Dantofema\LaravelSetup\Facades\Replace;
use Dantofema\LaravelSetup\Facades\Route;
use Dantofema\LaravelSetup\Facades\Text;
use Exception;
use Illuminate\Support\Facades\File;

trait CommandTrait
{
    use FileExistsTrait;

    protected array $config;
    private string $jetstreamPath = '/../Stubs/view/jetstream/basic.blade.php';
    private string $tailwindPath = '/../Stubs/view/tailwind/basic.blade.php';
    private string $stub;
    private string $type;
    private string $path;
    private Delete $delete;

    /**
     * @return array
     */
    public function getConfig (): array
    {
        return $this->config;
    }

    /**
     * @throws Exception
     */
    protected function init (string $type): bool
    {
        $this->type = $type;
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

        $this->setStub();

        return true;
    }

    protected function setStub (): void
    {
        if ($this->type !== 'view')
        {
            $this->path = self::STUB_PATH;
        } else
        {
            $this->path = $this->config['view']['jetstream']
                ? $this->jetstreamPath
                : $this->tailwindPath;
        }
//        $this->stub = Replace::config($this->config)->stub(file_get_contents(__DIR__ . $path))->type($type)->default();
        $this->stub = file_get_contents(__DIR__ . $this->path);
    }

    protected function put (string $content): bool|int
    {
        $replaceContent = Replace::config($this->config)
            ->stub($content)
            ->type($this->type)
            ->default();

        return File::put(Text::config($this->config)->path($this->type), $replaceContent);
    }

}