<?php

namespace Dantofema\LaravelSetup\Services\Tests;

use Dantofema\LaravelSetup\Services\FakerService;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class CreateService
{
    protected const CREATE_STUB = __DIR__ . '/../../Stubs/tests/create.stub';
    public FakerService $fakerService;

    #[Pure]
    public function __construct ()
    {
        $this->fakerService = new FakerService();
    }

    public function get (array $config, string $stub): string
    {
        $createStub = File::get(self::CREATE_STUB);
        $createStub = str_replace(':modelToLower:', strtolower($config['model']['name']), $createStub);
        $createStub = $this->replaceNewFileSection($config, $createStub);
        $createStub = $this->replaceVarSection($config, $createStub);
        $createStub = $this->replaceSetSection($config, $createStub);
        $createStub = $this->replaceAssertDatabaseSection($config, $createStub);
        $createStub = $this->replaceAssertExistsSection($config, $createStub);
        $createStub = gen()->config()->replace($config, 'test', $createStub);

        return $stub . gen()->config()->replace($config, 'test', $createStub);
    }

    private function replaceNewFileSection (array $config, string $stub): string
    {
        $newFiles = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                $newFiles .= "\$this->new"
                    . ucfirst($field['name']) . " = UploadedFile::fake()->image('file.jpg');" . PHP_EOL;
                $newFiles .= "\$test->set('new"
                    . ucfirst($field['name']) . "', \$this->new"
                    . ucfirst($field['name']) . ");";
            }
        }

        return str_replace(':newFiles:', $newFiles, $stub);
    }

    private function replaceVarSection (array $config, string $stub): string
    {
        $vars = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            if (gen()->field()->isDate($field))
            {
                $vars .= "$" . $field["name"] . " = new Carbon();" . PHP_EOL;
                continue;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $vars .= $field['form']['input']
                    ? '$' . $field['name'] . ' = '
                    . $field['relationship']['model'] . '::factory()->count(3)->create();' . PHP_EOL
                    : '';
                continue;
            }

            $vars .= $field['form']['input']
                ? '$' . $field['name'] . ' = ' .
                $this->fakerService->toTest($field) . ';' . PHP_EOL
                : '';
        }

        return str_replace(':vars:', $vars, $stub);
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

            if (gen()->field()->isDate($field))
            {
                $string .= "\$test->set('" . $field['name'] . "', $" . $field['name'] . "->format('d-m-Y'));" . PHP_EOL;
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
        $assertDatabase = '';

        foreach ($config['fields'] as $field)
        {
            if (gen()->field()->isFile($field))
            {
                continue;
            }

            if (gen()->field()->isBelongsToMany($field))
            {
                $assertDatabase .= "\$" . $field['relationship']['name']
                    . "->each(function (\$item) {\$this->assertModelExists(\$item);});" . PHP_EOL;
                continue;
            }

            if (gen()->field()->isDate($field))
            {
                $assertDatabase .= "\$this->assertEquals(" .
                    gen()->config()->model($config) . "::first()->" .
                    $field['name'] . "->format('Y-m-d'), $" .
                    $field['name'] . "->format('Y-m-d'));";
                continue;
            }

            $assertDatabaseHasStub = "\$this->assertDatabaseHas('"
                . gen()->config()->table($config)
                . "', ['" . $field['name'] . "' => :value:]);" . PHP_EOL;

            if ($field['name'] == 'slug')
            {
                $assertDatabase .= str_replace(':value:',
                    "Str::slug($" . $field['source'] . ")",
                    $assertDatabaseHasStub);
                continue;
            }

            $assertDatabase .= str_replace(':value:',
                "$" . $field['name'] . "",
                $assertDatabaseHasStub);
        }

        return str_replace(':assertDatabase:', $assertDatabase, $stub);
    }

    private function replaceAssertExistsSection (array $config, string $stub): string
    {
        $replace = '';
        $fields = gen()->field()->getFiles($config);

        foreach ($fields as $field)
        {
            $replace .= gen()->field()->replace(
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
