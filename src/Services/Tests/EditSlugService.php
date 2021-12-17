<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Illuminate\Support\Facades\File;

class EditSlugService
{

    protected const STUB_PATH = __DIR__ . '/../../Stubs/tests/edit-slug.stub';
    private string $editSlug = '';

    public function get (array $config): string
    {
        $slugField = gen()->field()->getSlug($config);

        if ( ! empty($slugField))
        {
            $this->editSlug = $this->replace($slugField, $config);
        }

        return $this->editSlug;
    }

    protected function replace (array $field, array $config): string
    {
        $stub = File::get(self::STUB_PATH);
        $stub = str_replace(
            ':field:',
            $field['source'],
            $stub
        );

        $stub = str_replace(
            ':view:',
            gen()->getRenderView($config),
            $stub
        );

        return str_replace(
            ':table:',
            gen()->config()->table($config),
            $stub
        );
    }
}
