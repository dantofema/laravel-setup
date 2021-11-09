<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Facades\Text;
use Dantofema\LaravelSetup\Traits\CommandTrait;
use Illuminate\Support\Facades\File;

class EditSlugService
{
    use CommandTrait;

    protected const STUB_PATH = __DIR__ . '/../../Stubs/tests/edit-slug.stub';

    public function get (array $config): string
    {
        if ($this->slugIsInArray($config['table']['columns']))
        {
            return str_replace(
                ':edit-slug:',
                '',
                File::get(self::STUB_PATH)
            );
        }

        return $this->replace(
            $this->getField($config['table']['columns']),
            File::get(self::STUB_PATH),
            $config);
    }

    protected function slugIsInArray (mixed $columns): bool
    {
        return ! $this->inArray('slug', $columns);
    }

    protected function replace (string $field, string $stub, array $config): string
    {
        $stub = str_replace(
            ':field:',
            $field,
            $stub);

        $stub = str_replace(
            ':view:',
            Text::config($config)->renderView('view'),
            $stub);

        $stub = str_replace(
            ':model:',
            Text::config($config)->name('model'),
            $stub);

        return str_replace(
            ':table:',
            Text::config($config)->name('table'),
            $stub);
    }

    protected function getField (mixed $columns): mixed
    {
        $field = 'missing';
        foreach ($columns as $column)
        {
            if (in_array('slug', $column))
            {
                $field = $column['from'];
            }
        }
        return $field;
    }
}