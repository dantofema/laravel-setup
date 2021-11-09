<?php

namespace Dantofema\LaravelSetup\Services\Views;

use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Support\Facades\File;

class FormModalService
{
    use CommandTrait;

    protected const STUB_PATH = __DIR__ . '/../../Stubs/view/jetstream/form-modal.blade.php';

    public function get (string $stub): string
    {
        $form = File::get(self::STUB_PATH);

        return str_replace(
            ':formModal:',
            $form,
            $stub
        );
    }
}