<?php

use Dantofema\LaravelSetup\Services\FileService;
use Illuminate\Support\Facades\File;

it('add WithSetup', closure: function () {
    $traitService = new FileService();

    $traitService->copy();

    expect(File::exists('app/Http/Livewire/Traits/WithSetup.php'))->toBeTrue();
    expect(shell_exec("php -l -f app/Http/Livewire/Traits/WithSetup.php"))
        ->toContain('No syntax errors detected');
});
