<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;

class ReplaceService
{
    private array $config;
    private string $stub;
    private string $type;

    public function stub (string $stub): ReplaceService
    {
        $this->stub = $stub;
        return $this;
    }

    public function config (array $config): ReplaceService
    {
        $this->config = $config;
        return $this;
    }

    public function type (string $type): ReplaceService
    {
        $this->type = $type;
        return $this;
    }

    public function field (array $field): string
    {
        $this->stub = str_replace(':disk:', Text::config($this->config)->name('disk'), $this->stub);
        $this->stub = str_replace(':testFakerFile:', "UploadedFile::fake()->image('file.jpg')", $this->stub);

        if ($field['form']['input'] === 'file')
        {
            if ( ! str_contains($this->stub, "use Illuminate\Http\UploadedFile;"))
            {
                str_replace(
                    '<?php',
                    '<?php' . PHP_EOL . "use Illuminate\Http\UploadedFile;",
                    $this->stub
                );
            }
        }

        $this->stub = str_replace(':field:', $field['name'], $this->stub);
        $this->stub = str_replace(':label:', $field['label'], $this->stub);

        $this->stub = empty($field['type'])
            ? $this->stub
            : str_replace(':type:', $field['type'], $this->stub);

        return str_replace(':formInput:', $field['form']['input'], $this->stub);
    }

    public function default (): string
    {
        $this->replaceTable();
        $this->replaceLivewire();
        $this->replaceRenderView();
        $this->replaceActingAs();
        $this->replaceFactory();
        $this->replaceModel();
        $this->replaceUse();
        $this->clearPhpEol();
        $this->replaceDisk();

        return $this->stub;
    }

    private function replaceTable (): void
    {
        $this->stub = str_replace(':tableName:', $this->config['table']['name'], $this->stub);
        $this->stub = str_replace(':table:', Text::config($this->config)->name('table'), $this->stub);
    }

    private function replaceLivewire (): void
    {
        $this->stub = str_replace(':livewire:', Text::config($this->config)->name('livewire'), $this->stub);
    }

    private function replaceRenderView (): void
    {
        $this->stub = str_replace(':renderView:', Text::config($this->config)->renderView(), $this->stub);
    }

    private function replaceActingAs (): void
    {
        $this->stub = str_replace(':actingAs:', $this->config['backend'] == true ? "actingAs(\$this->user);" : '', $this->stub);
    }

    private function replaceFactory (): void
    {
        $this->stub = str_replace(':factory:', Text::config($this->config)->name('model') . 'Factory', $this->stub);
    }

    private function replaceModel (): void
    {
        $this->stub = str_replace(':model:', Text::config($this->config)->name('model'), $this->stub);
    }

    public function replaceUse (): void
    {
        $useString = '';
        foreach ($this->config['fields'] as $field)
        {
            if (array_key_exists('relationships', $field))
            {
                $useString .= "use App\Models\\" . $field['relationships']['model'] . ";" . PHP_EOL;
            }
        }

        $useString .= str_contains($this->stub, 'BelongsTo')
            ? "use Illuminate\Database\Eloquent\Relations\BelongsTo;" . PHP_EOL
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

    private function clearPhpEol (): void
    {
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
        $this->stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $this->stub);
    }

    private function replaceDisk (): void
    {
        $this->stub = str_replace(':disk:', Text::config($this->config)->name('disk'), $this->stub);
    }

}