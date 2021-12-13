<?php

namespace Dantofema\LaravelSetup\Traits;

use Exception;
use Illuminate\Support\Facades\File;

trait CommandTrait
{

    protected array $config;
    protected array $properties;

    /**
     * @throws Exception
     */
    protected function init ($types): bool
    {
        gen()->setup();

        if ($this->option('force'))
        {
            gen()->delete($this->config, $types);
        }

        foreach ($types as $type)
        {
            $this->properties[] = [
                'stub' => file_get_contents(gen()->getStub($type)),
                'type' => $type,
            ];
        }

        return true;
    }

    protected function put (string $type, string $stub): void
    {
        File::put(
            gen()->getPath($this->config, $type),
            gen()->replaceFromConfig($this->config, $type, $stub)
        );
    }

}
