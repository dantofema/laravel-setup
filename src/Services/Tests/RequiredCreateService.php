<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Illuminate\Support\Facades\File;

class RequiredCreateService
{
    protected const REQUIRED_STUB = __DIR__ . '/../../Stubs/tests/create-required.stub';
    protected const REQUIRED_DATE_STUB = __DIR__ . '/../../Stubs/tests/create-required-date.stub';

    public function get (array $config, string $stub): string
    {
        foreach ($config['fields'] as $field)
        {
            if ( ! gen()->field()->isRequired($field))
            {
                continue;
            }

            if (gen()->field()->isFile($field))
            {
                $stub = $this->getTestFile($field, $config, $stub);
                continue;
            }

            if (gen()->field()->isDate($field))
            {
                $stub = $this->getTestDate($field, $config, $stub);
                continue;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $stub = $this->getTestBelongsToMany($field, $config, $stub);
                continue;
            }

            $stub = $this->getTestColumn($field, $config, $stub);
        }

        return $stub;
    }

    private function getTestFile (array $field, array $config, string $stub): string
    {
        $requiredStub = gen()->config()->replace($config, 'test', File::get(self::REQUIRED_STUB));

        $requiredStub = str_replace(':fieldTest:',
            'new' . ucfirst($field['name']),
            $requiredStub);

        $requiredStub = str_replace(':valueTest:',
            "''",
            $requiredStub);

        return $stub . gen()->field()->replace($field, $config, $requiredStub);
    }

    private function getTestDate (array $field, array $config, string $stub): string
    {
        $requiredStub = gen()->config()->replace($config, 'test', File::get(self::REQUIRED_DATE_STUB));

        $requiredStub = str_replace(':fieldTest:',
            $field['name'],
            $requiredStub);

        $requiredStub = str_replace(':valueTest:',
            "''",
            $requiredStub);

        return $stub . gen()->field()->replace($field, $config, $requiredStub);
    }

    private function getTestBelongsToMany (array $field, array $config, string $stub): string
    {
        $requiredStub = gen()->config()->replace($config, 'test', File::get(self::REQUIRED_STUB));

        $requiredStub = str_replace(':fieldTest:',
            $field['name'],
            $requiredStub);

        $requiredStub = str_replace(':valueTest:',
            "collect([])",
            $requiredStub);

        return $stub . gen()->field()->replace($field, $config, $requiredStub);
    }

    private function getTestColumn (array $field, array $config, string $stub): string
    {
        $requiredStub = gen()->config()->replace($config, 'test', File::get(self::REQUIRED_STUB));

        $requiredStub = str_replace(':fieldTest:',
            "editing." . $field['name'],
            $requiredStub);

        $requiredStub = str_replace(':valueTest:',
            "''",
            $requiredStub);

        return $stub . gen()->field()->replace($field, $config, $requiredStub);
    }

}
