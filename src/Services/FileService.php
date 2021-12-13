<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class FileService
{
    protected const LIVEWIRE_TRAITS_PATH = 'app/Http/Livewire/Traits/';
    protected const LIVEWIRE_TRAITS_PATH_SOURCE = '/../Stubs/livewire/';

    protected const COMPONENT_PATH = 'resources/views/components';
    protected const COMPONENT_PATH_SOURCE = '/../Stubs/components';

    protected const LAYOUT_PATH = 'resources/views/layouts';
    protected const LAYOUT_PATH_SOURCE = '/../Stubs/layouts';

    protected array $files = [
        'WithSetup',
    ];

    public function copy ()
    {
        File::ensureDirectoryExists(self::LIVEWIRE_TRAITS_PATH);

        foreach ($this->files as $file)
        {
            if ( ! File::exists(self::LIVEWIRE_TRAITS_PATH . $file))
            {
                $content = File::get(__DIR__ . self::LIVEWIRE_TRAITS_PATH_SOURCE . $file . '.stub');
                File::put(self::LIVEWIRE_TRAITS_PATH . $file . '.php', $content);
            }
        }

        File::copyDirectory(__DIR__ . self::COMPONENT_PATH_SOURCE, self::COMPONENT_PATH);

        File::copyDirectory(__DIR__ . self::LAYOUT_PATH_SOURCE, self::LAYOUT_PATH);
    }
}
