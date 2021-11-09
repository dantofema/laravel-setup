<?php

use Dantofema\LaravelSetup\Services\TraitService;
use Illuminate\Support\Facades\File;

it('add WithSaveNewImage', closure: function () {
    $traitService = new TraitService();

    $traitService->get();

    expect(File::exists('app/Http/Livewire/Traits/WithSaveNewImage.php'))->toBeTrue();
    expect(shell_exec("php -l -f app/Http/Livewire/Traits/WithSaveNewImage.php"))
        ->toContain('No syntax errors detected');
});

