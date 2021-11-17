<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Facades\Field;
use Dantofema\LaravelSetup\Facades\Replace;
use Illuminate\Support\Facades\File;

class RequiredEditService
{

    protected const REQUIRED_STUB = __DIR__ . '/../../Stubs/tests/edit-required.stub';

    public function get (array $config, string $stub): string
    {
        $requiredFields = $this->getRequiredFields($config['fields']);

        return str_replace(':required:', $this->getRequired($requiredFields, $config), $stub);
    }

    private function getRequiredFields ($fields): array
    {
        $requiredFields = [];

        foreach ($fields as $field)
        {
            $rule = Field::getRules($field);

            if ( ! empty($rule) and empty($rule['nullable']) and $field['name'] !== 'slug')
            {
                $requiredFields[] = $field;
            }
        }
        return $requiredFields;
    }

    private function getRequired (array $requiredFields, array $config): string
    {
        $required = '';

        foreach ($requiredFields as $field)
        {
            $requiredStub = Replace::config($config)->stub(File::get(self::REQUIRED_STUB))->type('test')->default();
            $required .= Replace::config($config)->stub($requiredStub)->field($field);
        }
        return $required;
    }
}