<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class RequiredEditService
{
    protected const REQUIRED_STUB = __DIR__ . '/../../Stubs/tests/edit-required.stub';

    public function get (array $config, string $stub): string
    {
        $requiredFields = $this->getRequiredFields($config['fields']);

        return str_replace(':required:', $this->getRequired($requiredFields, $config), $stub);
    }

    #[Pure] private function getRequiredFields ($fields): array
    {
        $requiredFields = [];

        foreach ($fields as $field)
        {
            $rule = gen()->field()->getRules($field);

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
            $requiredStub = gen()->replaceFromConfig($config, 'test', File::get(self::REQUIRED_STUB));
            $required .= gen()->replaceFromField($field, $config, $requiredStub);
        }

        return $required;
    }
}
