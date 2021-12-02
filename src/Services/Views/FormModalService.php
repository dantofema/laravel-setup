<?php

namespace Dantofema\LaravelSetup\Services\Views;

use Dantofema\LaravelSetup\Traits\CommandTrait;
use Exception;
use Illuminate\Support\Facades\File;

class FormModalService
{
    use CommandTrait;

    protected const STUB_PATH = __DIR__ . '/../../Stubs/view/jetstream/form-modal.blade.php.stub';
    protected const INPUT_FILE_PATH = __DIR__ . '/../../Stubs/view/jetstream/input-file.blade.php.stub';
    protected const INPUT_TEXT_PATH = __DIR__ . '/../../Stubs/view/jetstream/input-text.blade.php.stub';
    protected const TEXT_AREA_PATH = __DIR__ . '/../../Stubs/view/jetstream/text-area.blade.php.stub';
    protected const SELECT_PATH = __DIR__ . '/../../Stubs/view/jetstream/select.blade.php.stub';

    public function get (array $config, string $stub): string
    {
        $form = File::get(self::STUB_PATH);

        $form = str_replace(':fields:', $this->fields($config), $form);

        return str_replace(
            ':formModal:',
            $form,
            $stub
        );
    }

    /**
     * @throws Exception
     */
    private function fields (array $config): string
    {
        $fields = '';
        foreach ($config['fields'] as $field)
        {
            $fields .= match ($field['form']['input'])
            {
                'text' => $this->inputText($field),
                'textarea' => $this->textarea($field),
                'file' => $this->inputFile($field),
                'select' => $this->inputSelect($field),
                false => '',
                default => dump("El valor '{$field['form']['input']}' no match.")
            };
            $fields .= "\r\n";
        }

        return $fields;
    }

    private function inputText (array $field): string
    {
        $stub = File::get(self::INPUT_TEXT_PATH);
        $stub = str_replace(':label:', $field['label'], $stub);

        $stub = str_replace(
            ':belongsToMany:',
            $this->isBelongsToMany($field)
                ? "@foreach( \$this->" . $field['relationship']['name'] . " as \$item)<x-table.badge color='blue'>{{ \$item->name }}</x-table.badge>@endforeach"
                : '',
            $stub
        );

        $stub = $this->isBelongsToMany($field)
            ? str_replace(':editing:', '', $stub)
            : str_replace(':editing:', 'editing.', $stub);

        return $this->isBelongsToMany($field)
            ? str_replace(':field:', 'new' . $field['relationship']['model'], $stub)
            : str_replace(':field:', $field['name'], $stub);
    }

    private function isBelongsToMany (array $field): bool
    {
        return array_key_exists('relationship', $field) and $field['relationship']['type'] === 'belongsToMany';
    }

    private function textarea (array $field): string
    {
        $stub = File::get(self::TEXT_AREA_PATH);

        $stub = str_replace(':label:', $field['label'], $stub);

        return str_replace(':field:', $field['name'], $stub);
    }

    private function inputFile (array $field): string
    {
        $stub = File::get(self::INPUT_FILE_PATH);

        $stub = str_replace(':label:', $field['label'], $stub);

        return str_replace(':field:', 'newFile', $stub);
    }

    private function inputSelect (array $field): string
    {
        $stub = File::get(self::SELECT_PATH);

        $stub = str_replace(':field:', $field['name'], $stub);
        $stub = str_replace(':label:', $field['relationship']['label'], $stub);
        $stub = str_replace(':arrayItems:', $field['relationship']['table'], $stub);
        $stub = str_replace(':modelLower:', strtolower($field['relationship']['model']), $stub);

        return str_replace(':optionField:', $field['relationship']['searchable'], $stub);
    }
}
