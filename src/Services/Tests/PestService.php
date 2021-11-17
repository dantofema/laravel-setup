<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Support\Facades\File;

class PestService
{
    use CommandTrait;

    protected const STUB_PATH = __DIR__ . '/../../Stubs/tests/acting-as.stub';
    protected const PEST_PATH = 'tests/Pest.php';

    public function actingAs ()
    {
        $pest = File::get(self::PEST_PATH);
        $actingAs = File::get(self::STUB_PATH);
        str_contains($pest, 'actingAs') ?: File::put(self::PEST_PATH, $pest . $actingAs);
    }
}