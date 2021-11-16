<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Facades\Replace;
use Dantofema\LaravelSetup\Services\FakerService;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class EditService
{

    protected const EDIT_STUB = __DIR__ . '/../../Stubs/tests/edit.stub';
    protected const EDIT_FILE_STUB = __DIR__ . '/../../Stubs/tests/edit-file.stub';
    public FakerService $fakerService;

    #[Pure] public function __construct ()
    {
        $this->fakerService = new FakerService();
    }

    public function get (array $config, string $stub): string
    {
        $editStub = Replace::config($config, File::get(self::EDIT_STUB));
        $string = '';

        foreach ($config['fields'] as $field)
        {
            if ( ! empty($field['disk']))
            {
                continue;
            }

            if ($field['form']['input'])
            {
                $string .= Replace::field($field, $editStub);
                $string = str_replace(':faker:', $this->fakerService->toTest($field), $string);
            }
        }

        return $stub . Replace::end($string);
    }

    public function file (array $config, string $stub): string
    {
        $editStub = Replace::config($config, File::get(self::EDIT_FILE_STUB));
        $string = '';

        foreach ($config['fields'] as $field)
        {
            if (empty($field['disk']))
            {
                continue;
            }

            $string .= Replace::field($field, $editStub);
            $string = str_replace(':faker:', $this->fakerService->toTest($field), $string);
        }

        return $stub . Replace::end($string);
    }

}