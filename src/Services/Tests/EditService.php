<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Services\FakerService;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class EditService
{
    protected const EDIT_STUB = __DIR__ . '/../../Stubs/tests/edit.stub';
    protected const EDIT_PICKADAY_STUB = __DIR__ . '/../../Stubs/tests/edit-pickaday.stub';
    protected const EDIT_BELONGS_TO_MANY_STUB = __DIR__ . '/../../Stubs/tests/edit-belongs-to-many.stub';
    protected const EDIT_FILE_STUB = __DIR__ . '/../../Stubs/tests/edit-file.stub';
    public FakerService $fakerService;

    #[Pure] public function __construct ()
    {
        $this->fakerService = new FakerService();
    }

    public function get (array $config, string $stub): string
    {
        foreach ($config['fields'] as $field)
        {
            if ( ! gen()->field()->hasInput($field))
            {
                continue;
            }

            if (gen()->field()->isFile($field))
            {
                $stub = $this->getTestForFile($field, $config, $stub);
                continue;
            }

            if (gen()->field()->isDate($field))
            {
                $stub = $this->getTestForPickaday($field, $config, $stub);
                continue;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $stub = $this->getTestForBelongsToMany($field, $config, $stub);
                continue;
            }

            $stub = $this->getTestForColumn($field, $config, $stub);
        }

        return $stub;
    }

    private function getTestForFile (array $field, array $config, string $stub): string
    {
        $editStub = gen()->field()->replace($field, $config, File::get(self::EDIT_FILE_STUB));
        $editStub = str_replace(':faker:', $this->fakerService->toTest($field), $editStub);

        return $stub . gen()->config()->replace($config, 'test', $editStub);
    }

    private function getTestForPickaday (mixed $field, array $config, mixed $stub): string
    {
        $fileStub = gen()->field()->replace($field, $config, File::get(self::EDIT_PICKADAY_STUB));
        $fileStub = str_replace(':faker:', "'22-11-2010'", $fileStub);
        $fileStub = str_replace(':setField:', $field['name'], $fileStub);

        $fileStub = str_replace(':dateTimeTest:',
            ! gen()->field()->isDateTime($config)
                ? "->format('Y-m-d')"
                : "",
            $fileStub);

        return $stub . gen()->config()->replace($config, 'test', $fileStub);
    }

    private function getTestForBelongsToMany (mixed $field, array $config, mixed $stub): string
    {
        $fileStub = gen()->field()->replace($field, $config, File::get(self::EDIT_BELONGS_TO_MANY_STUB));

        $fileStub = str_replace(':fieldRelationship:',
            $field['relationship']['name'],
            $fileStub);

        $fileStub = str_replace(':relationshipModel:',
            $field['relationship']['model'],
            $fileStub);

        $fileStub = str_replace(':faker:',
            $field['relationship']['model'] . "::inRandomOrder()->first()",
            $fileStub);

        $fileStub = str_replace(':relationSearchable:',
            $field['relationship']['searchable'],
            $fileStub);

        $fileStub = str_replace(':seedTest:',
            $field['relationship']['model'] . "::factory(10)->create();",
            $fileStub);

        $fileStub = str_replace(':setField:',
            $field['relationship']['name'],
            $fileStub);

        return $stub . gen()->config()->replace($config, 'test', $fileStub);
    }

    private function getTestForColumn (mixed $field, array $config, string $stub): string
    {
        $fileStub = '';
        if ($field['form']['input'])
        {
            $fileStub = gen()->field()->replace($field, $config, File::get(self::EDIT_STUB));
            $fileStub = str_replace(':faker:', $this->fakerService->toTest($field), $fileStub);
            $fileStub = str_replace(':setField:', 'editing.' . $field['name'], $fileStub);
        }
        return $stub . gen()->config()->replace($config, 'test', $fileStub);
    }

}
