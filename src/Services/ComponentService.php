<?php

namespace Dantofema\LaravelSetup\Services;

use Illuminate\Support\Facades\File;

class ComponentService
{
    protected const DESTINATION = 'resources/views/components';
    protected const SOURCE = '/../Stubs/components';

    public function copy (): bool
    {
        return File::copyDirectory(__DIR__ . self::SOURCE, self::DESTINATION);
    }
}