<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Facades\Delete;
use Dantofema\LaravelSetup\Facades\Generate;
use Dantofema\LaravelSetup\Facades\Replace;
use Dantofema\LaravelSetup\Facades\Text;
use Exception;
use Illuminate\Support\Facades\File;

trait CommandTrait
{
    use FileExistsTrait;

    protected array $config;
    private string $jetstreamPath = '/../Stubs/view/jetstream/basic.blade.php.stub';
    private string $tailwindPath = '/../Stubs/view/tailwind/basic.blade.php.stub';
    private string $stub;
    private string $type;
    private Delete $delete;

    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @throws Exception
     */
    protected function init(string $type): bool
    {
        $this->type = $type;
        Generate::setup();

        $this->configFileExists();

        $this->config = include $this->argument('path');

        if ($this->option('force')) {
            Generate::delete($this->config, $type);
        }

        if ($type == 'livewire') {
            Generate::addRoute($this->config);
        }

        $this->exists($type);

        $this->setStub();

        return true;
    }

    protected function setStub(): void
    {
        if ($this->type !== 'view') {
            $path = self::STUB_PATH;
        } else {
            $path = $this->config['view']['jetstream']
                ? $this->jetstreamPath
                : $this->tailwindPath;
        }

        $this->stub = file_get_contents(__DIR__ . $path);
    }

    protected function put(string $content): bool|int
    {
        $replaceContent = Replace::config($this->config)
            ->stub($content)
            ->type($this->type)
            ->default();

        return File::put(Text::config($this->config)->path($this->type), $replaceContent);
    }
}
