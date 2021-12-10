<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Services\GenerateService;
use Exception;
use Illuminate\Support\Facades\File;

trait CommandTrait
{
    use FileExistsTrait;

    protected array $config;
    protected array $stubs;
    protected array $types;
    protected GenerateService $generateService;

    public function getConfig (): array
    {
        return $this->config;
    }

    /**
     * @throws Exception
     */
    protected function init (): bool
    {
        $this->generateService = new GenerateService();

        $this->generateService->setup();

        $this->configFileExists();

        $this->config = include $this->argument('path');

        if ($this->option('force'))
        {
            $this->generateService->delete($this->config, $this->types);
        }

        foreach ($this->types as $type)
        {
            if (str_contains($type, 'livewire'))
            {
                $this->generateService->addRoute($this->config);
            }
        }

        $this->exists();

        $this->setStubs();

        return true;
    }

    protected function setStubs (): void
    {
        foreach ($this->types as $type)
        {
            $this->stubs[] = [
                $type => file_get_contents($this->generateService->getStub($type)),
            ];
        }
    }

    protected function put (string $content): void
    {
        foreach ($this->types as $type)
        {
            File::put(
                $this->generateService->getPath($this->config, $type),
                $this->generateService->replaceFromConfig($this->config, $type, $content)
            );
        }
    }
}
