<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Illuminate\Support\Facades\File;

class RequiredEditService
{
    protected const REQUIRED_STUB = __DIR__ . '/../../Stubs/tests/edit-required.stub';

    public function get (array $config, string $stub): string
    {
        return str_replace(
            ':required:',
            $this->getRequired(gen()->config()->requiredFields($config), $config),
            $stub
        );
    }

    private function getRequired (array $requiredFields, array $config): string
    {
        $required = '';

        foreach ($requiredFields as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            $requiredStub = gen()->config()->replace($config, 'test', File::get(self::REQUIRED_STUB));
            $required .= gen()->field()->replace($field, $config, $requiredStub);
        }

        return $required;
    }
}
