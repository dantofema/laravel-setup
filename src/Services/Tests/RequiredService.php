<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Traits\TestTrait;
use Illuminate\Support\Facades\File;

class RequiredService
{
    use TestTrait;

    protected const REQUIRED_STUB = __DIR__ . '/../../Stubs/tests/required.stub';

    public function get (array $config, string $stub): string
    {
        $columns = [];

        foreach ($config['table']['columns'] as $column)
        {
            in_array('nullable', $column) ?: array_push($columns, $column);
        }

        $required = '';

        foreach ($columns as $column)
        {
            $requiredStub = File::get(self::REQUIRED_STUB);

            $requiredStub = str_replace(
                ':field:',
                $column[1],
                $requiredStub);

            $requiredStub = $this->getLivewire($config, $requiredStub);

            $requiredStub = $this->getModel($config, $requiredStub);
            $requiredStub = $this->actingAs($config, $requiredStub);

            $required .= $this->getTable($config, $requiredStub);
        }

        return str_replace(
            ':required:',
            $required,
            $stub);
    }
}