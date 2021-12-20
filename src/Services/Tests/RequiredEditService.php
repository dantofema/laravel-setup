<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Illuminate\Support\Facades\File;

class RequiredEditService
{
    protected const REQUIRED_STUB = __DIR__ . '/../../Stubs/tests/edit-required.stub';
    protected const REQUIRED_BELONGS_TO_MANY_STUB = __DIR__ . '/../../Stubs/tests/edit-required-belongs-to-many.stub';
    protected const REQUIRED_DATE_STUB = __DIR__ . '/../../Stubs/tests/edit-required-date.stub';

    public function get (array $config, string $stub): string
    {
        foreach (gen()->config()->requiredFields($config) as $field)
        {
            if ( ! gen()->field()->hasInput($field)
                or gen()->field()->isFile($field))
            {
                continue;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $stub = $this->getTestBelongsToMany($field, $config, $stub);
                continue;
            }

            if (gen()->field()->isDate($field))
            {
                $stub = $this->getTestForPickaday($field, $config, $stub);
                continue;
            }
            $stub = $this->getTestForColumn($field, $config, $stub);
        }

        return $stub;
    }

    private function getTestBelongsToMany (mixed $field, array $config, string $stub): string
    {
        $requiredStub = gen()->field()->replace($field, $config, File::get(self::REQUIRED_BELONGS_TO_MANY_STUB));

        $requiredStub = str_replace(
            ':relationshipName:',
            strtolower($field['relationship']['name']),
            $requiredStub
        );

        return $stub . $requiredStub;
    }

    private function getTestForPickaday (array $field, array $config, mixed $stub): string
    {
        $requiredStub = gen()->field()->replace($field, $config, File::get(self::REQUIRED_DATE_STUB));
        $requiredStub = str_replace(':setField:', $field['name'], $requiredStub);

        $requiredStub = str_replace(':dateTimeTest:',
            ! gen()->field()->isDateTime($config)
                ? "->format('Y-m-d')"
                : "",
            $requiredStub);

        return $stub . $requiredStub;
    }

    private function getTestForColumn (array $field, array $config, mixed $stub): string
    {
        $requiredStub = gen()->field()->replace($field, $config, File::get(self::REQUIRED_STUB));

        return $stub . $requiredStub;
    }
}
