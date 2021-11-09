<?php

namespace Dantofema\LaravelSetup\Traits;

use Dantofema\LaravelSetup\Facades\Text;

trait TestTrait
{
    private function getLivewire (array $config, string $stub): string
    {
        return str_replace(
            ':livewire:',
            Text::config($config)->name('livewire') . '::class',
            $stub);
    }

    private function getModel (array $config, string $stub): string|array
    {
        return str_replace(
            ':model:',
            Text::config($config)->name('model'),
            $stub);
    }

    private function getTable (array $config, string $stub): string
    {
        return str_replace(
            ':table:',
            Text::config($config)->name('table'),
            $stub);
    }

    private function actingAs (array $config, string $stub): string
    {
        $stub = str_replace(
            '):actingAs:',
            $config['backend'] == true ? ")\r\n->actingAs(\$this->user)" : '',
            $stub);

        return str_replace(
            ':actingAs:',
            $config['backend'] == true ? "actingAs(\$this->user)" : '',
            $stub);
    }
}