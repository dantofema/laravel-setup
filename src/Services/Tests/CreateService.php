<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Services\FakerService;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class CreateService
{
    protected const SAVE_STUB = __DIR__ . '/../../Stubs/tests/create.stub';
    public FakerService $fakerService;

    #[Pure]
    public function __construct ()
    {
        $this->fakerService = new FakerService();
    }

    public function get (array $config, string $stub): string
    {
        $saveStub = File::get(self::SAVE_STUB);
        $saveStub = $this->replaceNewFileSection($config, $saveStub);
        $saveStub = $this->replaceVarSection($config, $saveStub);
        $saveStub = $this->replaceSetSection($config, $saveStub);
        $saveStub = $this->replaceAssertDatabaseSection($config, $saveStub);
        $saveStub = $this->replaceAssertExistsSection($config, $saveStub);
        $saveStub = gen()->config()->replace($config, 'test', $saveStub);

        return $stub . str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $saveStub);
    }

    private function replaceNewFileSection (array $config, string $stub): string
    {
        $newFile = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                $newFile = "\$this->newFile = UploadedFile::fake()->image('file.jpg');" . PHP_EOL;
                $newFile .= "\$test->set('newFile', \$this->newFile);";
            }
        }

        return str_replace(':newFile:', $newFile, $stub);
    }

    private function replaceVarSection (array $config, string $stub): string
    {
        $string = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $string .= $field['form']['input']
                    ? '$' . $field['name'] . ' = '
                    . $field['relationship']['model'] . '::take(3)->get();' . PHP_EOL
                    : '';
                continue;
            }

            $string .= $field['form']['input']
                ? '$' . $field['name'] . ' = ' .
                $this->fakerService->toTest($field) . ';' . PHP_EOL
                : '';
        }

        return str_replace(':vars:', $string, $stub);
    }

    private function replaceSetSection (array $config, string $stub): string
    {
        $string = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $string .= $field['form']['input']
                    ? "\$test->set('" . $field['name'] . "', $" . $field['name'] . ");" . PHP_EOL
                    : '';
                continue;
            }

            $string .= $field['form']['input']
                ? "\$test->set('editing." . $field['name'] . "', $" . $field['name'] . ");" . PHP_EOL
                : '';
        }

        return str_replace(':sets:', $string, $stub);
    }

    private function replaceAssertDatabaseSection (array $config, string $stub): string
    {
        $replace = '';
        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isBelongsToMany($field))
            {
                $replace .= "\$" . $field['relationship']['name']
                    . "->each(function (\$item) {\$this->assertModelExists(\$item);});" . PHP_EOL;
                continue;
            }

            $string = '$this->assertDatabaseHas(:table:, :data:);' . PHP_EOL;
            $data = '[';

            if ($field['form']['input'] === 'file')
            {
                continue;
            } else if ($field['name'] == 'slug')
            {
                $data .= "'" . $field['name'] . "' => Str::slug($" . $field['source'] . "),";
            } else
            {
                $data .= "'" . $field['name'] . "' => $" . $field['name'] . ",";
            }
            $data .= ']';
            $string = str_replace(':data:', $data, $string);
            $replace .= str_replace(':table:', "'" . gen()->config()->table($config) . "'", $string);
        }

        return str_replace(':assertDatabase:', $replace, $stub);
    }

    private function replaceAssertExistsSection (array $config, string $stub): string
    {
        $replace = '';
        $field = gen()->field()->getFile($config);
        if ( ! empty($field))
        {
            $replace = gen()->field()->replace(
                $field,
                $config,
                "Storage::disk(':disk:')->assertExists(:model:::first()->:field:);");
        }

        return str_replace(
            ':assertExists:',
            $replace,
            $stub
        );
    }
}
