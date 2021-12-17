<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Services\FakerService;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class EditService
{
    protected const EDIT_STUB = __DIR__ . '/../../Stubs/tests/edit.stub';
    protected const EDIT_FILE_STUB = __DIR__ . '/../../Stubs/tests/edit-file.stub';
    public FakerService $fakerService;

    #[Pure]
    public function __construct ()
    {
        $this->fakerService = new FakerService();
    }

    public function get (array $config, string $stub): string
    {
        $string = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            if ($field['form']['input'])
            {
                $string .= gen()->config()->replace(
                    $config,
                    'test',
                    gen()->config()->replace($config, 'test', self::EDIT_STUB)
                );

                $string = str_replace(':faker:', $this->fakerService->toTest($field), $string);
            }
        }

        return $stub . gen()->config()->replace($config, 'test', $string);
    }

    public function file (array $config, string $stub): string
    {
        $editStub = gen()->config()->replace($config, 'test', File::get(self::EDIT_FILE_STUB));
        $string = '';

        foreach ($config['fields'] as $field)
        {
            if ($field['form']['input'] !== 'file')
            {
                continue;
            }

            $string .= gen()->field()->replace($field, $config, $editStub);
            $string = str_replace(':faker:', $this->fakerService->toTest($field), $string);
        }

        return $stub . gen()->config()->replace($config, 'test', $string);
    }
}
