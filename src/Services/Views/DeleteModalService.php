<?php

namespace Dantofema\LaravelSetup\Services\Views;

use Illuminate\Support\Facades\File;

class DeleteModalService
{

    protected const STUB_PATH = __DIR__ . '/../../Stubs/view/jetstream/delete-modal.blade.php';

    public function get (string $stub)
    {
        $form = File::get(self::STUB_PATH);

        return str_replace(
            ':deleteModal:',
            $form,
            $stub
        );
    }
}