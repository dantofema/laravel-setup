<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Facades\Generate;
use Dantofema\LaravelSetup\Facades\Replace;
use Dantofema\LaravelSetup\Facades\Text;
use Exception;
use Illuminate\Support\Facades\File;

trait CommandTrait
{
    use FileExistsTrait;

    protected array $config;
    private array $stubs;
    private string $type;

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
        Generate::setup();

        $this->configFileExists();

        $this->config = include $this->argument('path');

        if ($this->option('force'))
        {
            Generate::delete($this->config, $type);
        }

        if ($type == 'livewire')
        {
            Generate::addRoute($this->config);
        }

        $this->exists($type);

        $this->setStubs($type);

        return true;
    }

    protected function setStubs (string $type): void
    {
        if ( ! $this->config['modal'] and ($type === 'view' or $type === 'livewire'))
        {
            $this->stubs = [
                $type . '.collection' => file_get_contents(Generate::getStub($type) . '.collection'),
                $type . '.model' => file_get_contents(Generate::getStub($type) . '.model'),
            ];

            return;
        }

        $this->stubs = [
            $type => file_get_contents(Generate::getStub($type)),
        ];
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
