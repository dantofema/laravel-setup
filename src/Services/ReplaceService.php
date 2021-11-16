<?php

namespace Dantofema\LaravelSetup\Services;

use Dantofema\LaravelSetup\Facades\Text;

class ReplaceService
{

    public function config (array $config, string $stub): string
    {
        $stub = str_replace(':table:', Text::config($config)->name('table'), $stub);
        $stub = str_replace(':livewire:', Text::config($config)->name('livewire'), $stub);
        $stub = str_replace(':tableName:', $config['table']['name'], $stub);
        $stub = str_replace(':actingAs:', $config['backend'] == true ? "actingAs(\$this->user);" : '', $stub);
        return str_replace(':model:', Text::config($config)->name('model'), $stub);
    }

    public function field (array $field, string $stub): string
    {
        if ( ! empty($field['disk']))
        {
            $stub = str_replace(':disk:', $field['disk'], $stub);
            $stub = str_replace(':testFakerFile:', "UploadedFile::fake()->image('file.jpg')", $stub);

            if ( ! str_contains($stub, "use Illuminate\Http\UploadedFile;"))
            {
                str_replace('<?php', '<?php' . PHP_EOL . "use Illuminate\Http\UploadedFile;", $stub);
            }
        }

        $stub = str_replace(':field:', $field['name'], $stub);
        $stub = str_replace(':label:', $field['label'], $stub);
        $stub = empty($field['type']) ? $stub : str_replace(':type:', $field['type'], $stub);
        return str_replace(':formInput:', $field['form']['input'], $stub);
    }

    public function end (string $stub): string
    {
        $stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $stub);
        $stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $stub);
        $stub = str_replace(PHP_EOL . PHP_EOL . PHP_EOL, PHP_EOL, $stub);
        return str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $stub);
    }

}