<?php

namespace Dantofema\LaravelSetup\Services;

use JetBrains\PhpStorm\Pure;

class ReplaceService
{
    private array $config;
    private string $stub;
    private string $type;
    private NameService $nameService;

    #[Pure] public function __construct ()
    {
        $this->nameService = new NameService();
    }

    public function fromField (array $field, array $config, string $stub): string
    {
        $stub = str_replace(
            ':disk:',
            $this->nameService->get($config, 'disk'),
            $stub);

        $stub = str_replace(':testFakerFile:',
            "UploadedFile::fake()->image('file.jpg')",
            $stub);

        if ($field['form']['input'] === 'file')
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
        $this->stub = str_replace(':table:', Text::config($this->config)->name('table'), $this->stub);
    }

    private function livewire (): void
    {
        $this->stub = str_replace(':livewire:', Text::config($this->config)->name('livewire'), $this->stub);
    }

    private function renderView (): void
    {
        $this->stub = str_replace(':renderView:', Text::config($this->config)->renderView(), $this->stub);
    }

    private function actingAs (): void
    {
        $this->stub = str_replace(':actingAs:', $this->config['backend'] == true ? "actingAs(\$this->user);" : '', $this->stub);
    }

    private function factory (): void
    {
        $this->stub = str_replace(':factory:', Text::config($this->config)->name('model') . 'Factory', $this->stub);
    }

    private function model (): void
    {
        $this->stub = str_replace(':model:', Text::config($this->config)->name('model'), $this->stub);
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

        $useString .= str_contains($this->stub, 'BelongsTo')
            ? "use Illuminate\Database\Eloquent\Relations\BelongsTo;" . PHP_EOL
            : null;

        $useString .= str_contains($this->stub, 'BelongsToMany')
            ? "use Illuminate\Database\Eloquent\Relations\BelongsToMany;" . PHP_EOL
            : null;

        if ($this->type !== 'model')
        {
            $useString .= 'use ' . Text::config($this->config)->namespace('model') . PHP_EOL;
        }

        $useString .= str_contains($this->stub, 'Carbon::')
            ? "use Carbon\Carbon;" . PHP_EOL
            : null;

        $useString .= str_contains($this->stub, 'UploadedFile::')
            ? "use Illuminate\Http\UploadedFile;" . PHP_EOL
            : null;

        $useString .= str_contains($this->stub, 'Str::')
            ? "use Illuminate\Support\Str;" . PHP_EOL
            : null;

        if ($this->type !== 'test')
        {
            $useString .= str_contains($this->stub, 'Storage::')
                ? "use Storage;" . PHP_EOL
                : null;
        }

        $this->stub = str_replace(':useDefault:', $useString, $this->stub);
    }

    private function phpEol (): void
    {
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
    }

    private function disk (): void
    {
        $this->stub = str_replace(':disk:', Text::config($this->config)->name('disk'), $this->stub);
    }
}
