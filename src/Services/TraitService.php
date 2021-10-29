<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class TraitService
{
    protected const APP_HTTP_LIVEWIRE_TRAITS = 'app/Http/Livewire/Traits';

    public function withSaveNewImage ()
    {
        File::ensureDirectoryExists(self::APP_HTTP_LIVEWIRE_TRAITS);

        if ( ! File::exists(self::APP_HTTP_LIVEWIRE_TRAITS . '/WithSaveNewImage.php'))
        {
            $content = File::get(__DIR__ . '/../Stubs/livewire/WithSaveNewImage.stub');
            File::put(self::APP_HTTP_LIVEWIRE_TRAITS . '/WithSaveNewImage.php', $content);
        }
    }
}