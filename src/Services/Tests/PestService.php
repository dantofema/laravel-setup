<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Illuminate\Support\Facades\File;

class PestService
{

    protected const STUB_PATH = __DIR__ . '/../../Stubs/tests/acting-as.stub';
    protected const PEST_PATH = 'tests/Pest.php';

    public function actingAs ()
    {
        $pest = File::get(self::PEST_PATH);
        $actingAs = File::get(self::STUB_PATH);
        $actingAs .= str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $actingAs);
        str_contains($pest, 'actingAs') ?: File::put(self::PEST_PATH, $pest . $actingAs);
    }
}
