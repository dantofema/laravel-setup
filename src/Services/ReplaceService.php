<?php

namespace Dantofema\LaravelSetup\Services;

class ReplaceService
{
    private array $config;
    private string $stub;
    private string $type;

    public function fromField (array $field, array $config, string $stub): string
    {
        $stub = str_replace(
            ':disk:',
            gen()->config()->disk($config),
            $stub);

        $stub = str_replace(':testFakerFile:',
            "UploadedFile::fake()->image('file.jpg')",
            $stub);

        if (gen()->field()->isFile($field))
        {
            if ( ! str_contains($stub, "use Illuminate\Http\UploadedFile;"))
            {
                str_replace(
                    '<?php',
                    '<?php' . PHP_EOL . "use Illuminate\Http\UploadedFile;",
                    $stub
                );
            }
        }

        $stub = str_replace(':field:', $field['name'], $stub);
        $stub = str_replace(':label:', $field['label'], $stub);
        $stub = str_replace(':newFile:', 'new' . ucfirst($field['name']), $stub);
        $stub = empty($field['type'])
            ? $stub
            : str_replace(':type:', $field['type'], $stub);

        return str_replace(':formInput:', $field['form']['input'], $stub);
    }

    public function fromConfig (array $config, string $type, string $stub): string
    {
        $this->config = $config;
        $this->type = $type;
        $this->stub = $stub;
        $this->table();
        $this->livewire();
        $this->renderView();
        $this->actingAs();
        $this->factory();
        $this->model();
        $this->use();
        $this->phpEol();
        $this->disk();

        return $this->stub;
    }

    private function table (): void
    {
        $this->stub = str_replace(':tableName:', $this->config['table']['name'], $this->stub);
        $this->stub = str_replace(':table:', gen()->config()->table($this->config), $this->stub);
    }

    private function livewire (): void
    {
        $this->stub = str_replace(':livewire:', gen()->config()->livewire($this->config), $this->stub);
    }

    private function renderView (): void
    {
        $this->stub = str_replace(':renderViewCollection:', gen()->path()->renderView($this->config),
            $this->stub);

        $this->stub = str_replace(':renderViewModel:', gen()->path()->isModel()->renderView($this->config),
            $this->stub);

        $this->stub = str_replace(':renderViewAllInOne:', gen()->path()->renderView($this->config),
            $this->stub);
    }

    private function actingAs (): void
    {
        $this->stub = str_replace(':actingAs:', $this->config['backend'] == true ? "actingAs(\$this->user);" : '', $this->stub);
    }

    private function factory (): void
    {
        $this->stub = str_replace(':factory:', gen()->config()->model($this->config) . 'Factory', $this->stub);
    }

    private function model (): void
    {
        $this->stub = str_replace(':model:', gen()->config()->model($this->config), $this->stub);
    }

    private function use (): void
    {
        $useString = '';

        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists('relationship', $field))
            {
                $useString .= "use App\Models\\" . $field['relationship']['model'] . ";" . PHP_EOL;
            }
        }

        if ($this->type !== 'test')
        {
            $useString .= str_contains($this->stub, 'Storage::')
                ? "use Storage;" . PHP_EOL
                : null;
        }

        if ($this->type !== 'model')
        {
            $useString .= "use App\Models\\" . gen()->config()->model($this->config) . ';' . PHP_EOL;
        }

        $useString .= str_contains($this->stub, 'BelongsTo')
            ? "use Illuminate\Database\Eloquent\Relations\BelongsTo;" . PHP_EOL
            : null;

        $useString .= str_contains($this->stub, 'BelongsToMany')
            ? "use Illuminate\Database\Eloquent\Relations\BelongsToMany;" . PHP_EOL
            : null;

        $useString .= str_contains($this->stub, 'Carbon')
            ? "use Carbon\Carbon;" . PHP_EOL
            : null;

        $useString .= str_contains($this->stub, 'UploadedFile::')
            ? "use Illuminate\Http\UploadedFile;" . PHP_EOL
            : null;

        $useString .= str_contains($this->stub, 'Str::')
            ? "use Illuminate\Support\Str;" . PHP_EOL
            : null;

        $this->stub = str_replace(':useDefault:', $useString, $this->stub);
    }

    private function phpEol (): void
    {
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
        $this->stub = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
    }

    private function disk (): void
    {
        $this->stub = str_replace(':disk:', gen()->config()->disk($this->config), $this->stub);
    }
}
