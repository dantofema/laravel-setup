<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class TraitService
{
    protected const APP_HTTP_LIVEWIRE_TRAITS = 'app/Http/Livewire/Traits/';
    protected const STUB_PATH = '/../Stubs/livewire/';
    protected array $files = [
        'WithSaveNewImage',
        'WithSetup',
    ];

    public function get ()
    {
        File::ensureDirectoryExists(self::APP_HTTP_LIVEWIRE_TRAITS);

        foreach ($this->files as $file)
        {
            if ( ! File::exists(self::APP_HTTP_LIVEWIRE_TRAITS . $file))
            {
                $content = File::get(__DIR__ . self::STUB_PATH . $file . '.stub');
                File::put(self::APP_HTTP_LIVEWIRE_TRAITS . $file . '.php', $content);
            }
        }
    }

}