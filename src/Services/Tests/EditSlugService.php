<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Support\Facades\File;

class EditSlugService
{
    use CommandTrait;

    protected const STUB_PATH = __DIR__ . '/../../Stubs/tests/edit-slug.stub';
    private string $editSlug = '';

    public function get(array $config): string
    {
        $slugField = Field::config($config)->getSlug();

        if (! empty($slugField)) {
            $this->editSlug = $this->replace($slugField, $config);
        }

        return $this->editSlug;
    }

    protected function replace(array $field, array $config): string
    {
        $stub = File::get(self::STUB_PATH);
        $stub = str_replace(
            ':field:',
            $field['source'],
            $stub
        );

        $stub = str_replace(
            ':view:',
            Text::config($config)->renderView('view'),
            $stub
        );

        return str_replace(
            ':table:',
            Text::config($config)->name('table'),
            $stub
        );
    }
}
