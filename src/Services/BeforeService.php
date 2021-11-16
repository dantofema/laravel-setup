<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Services\Tests\PestService;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class BeforeService
{
    private PestService $pest;
    private array $directories = [
        'database/factories/',
        'app/Http/Livewire',
        'app/Http/Livewire/Backend/',
        'app/Http/Livewire/Frontend/',
        'database/migrations/',
        'app/Models/',
        'tests/Feature',
        'tests/Feature/Backend/',
        'tests/Feature/Frontend/',
        'resources/views/livewire',
        'resources/views/livewire/backend/',
        'resources/views/livewire/frontend/',
        'resources/views/components/',
    ];
    private FileService $file;

    #[Pure] public function __construct ()
    {
        $this->pest = new PestService();
        $this->file = new FileService();
    }

    public function setup ()
    {
        foreach ($this->directories as $directory)
        {
            File::ensureDirectoryExists($directory);
        }

        $this->file->copy();

        $this->pest->actingAs();
    }

}