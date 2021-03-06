<?php

namespace Dantofema\LaravelSetup\Services\Views;

use Exception;
use Illuminate\Support\Facades\File;

class FormCrudService
{

    protected const STUB_PATH_ALL_IN_ONE = __DIR__ . '/../../Stubs/view/jetstream/form-crud-modal.blade.php.stub';
    protected const STUB_PATH = __DIR__ . '/../../Stubs/view/form-crud.blade.php.stub';
    protected const INPUT_FILE_PATH = __DIR__ . '/../../Stubs/view/jetstream/input-file.blade.php.stub';
    protected const INPUT_TEXT_PATH = __DIR__ . '/../../Stubs/view/jetstream/input-text.blade.php.stub';
    protected const PICKADAY_PATH = __DIR__ . '/../../Stubs/view/jetstream/pickaday.blade.php.stub';
    protected const INPUT_TEXT_BELONGS_TO_MANY_PATH = __DIR__ . '/../../Stubs/view/jetstream/input-text-belongs-to-many.blade.php.stub';
    protected const TEXT_AREA_PATH = __DIR__ . '/../../Stubs/view/jetstream/text-area.blade.php.stub';
    protected const SELECT_PATH = __DIR__ . '/../../Stubs/view/jetstream/select.blade.php.stub';
    protected const TRIX_PATH = __DIR__ . '/../../Stubs/view/jetstream/trix.blade.php.stub';

    /**
     * @throws Exception
     */
    public function get (array $config, string $stub): string
    {
        $form = gen()->config()->isAllInOne($config)
            ? File::get(self::STUB_PATH_ALL_IN_ONE)
            : File::get(self::STUB_PATH);

        $form = str_replace(':fields:', $this->fields($config), $form);

        return str_replace(
            ':formCrud:',
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
                'text' => $this->isBelongsToMany($field)
                    ? $this->inputTextBelongsToMany($field)
                    : $this->inputText($field),
                'textarea' => $this->textarea($field),
                'file' => $this->inputFile($field),
                'select' => $this->inputSelect($field),
                'date' => $this->inputDate($field),
                'boolean' => $this->inputBoolean($field),

                false => '',
                default => dump("FormCrudService: El valor '{$field['form']['input']}' en el campo '{$field['name']}' en el model '" . gen()->config()->model($config) . "' no match.")
            };
            $fields .= PHP_EOL;
        }

        return $fields;
    }

    private function isBelongsToMany (array $field): bool
    {
        return array_key_exists('relationship', $field) and $field['relationship']['type'] === 'belongsToMany';
    }

    private function inputTextBelongsToMany (array $field): string
    {
        $stub = File::get(self::INPUT_TEXT_BELONGS_TO_MANY_PATH);
        $stub = str_replace(':label:', $field['label'], $stub);

        $stub = str_replace(
            ':name:',
            $field['relationship']['name'],
            $stub
        );

        $stub = str_replace(
            ':searchable:',
            $field['relationship']['searchable'],
            $stub
        );

        $stub = str_replace(
            ':model:',
            $field['relationship']['model'],
            $stub
        );

        $stub = str_replace(
            ':modelLower:',
            strtolower($field['relationship']['model']),
            $stub
        );

        return str_replace(':field:', 'new' . $field['relationship']['model'], $stub);
    }

    private function inputText (array $field): string
    {
        $stub = File::get(self::INPUT_TEXT_PATH);
        $stub = str_replace(':label:', $field['label'], $stub);

        $stub = str_replace(':editing:', 'editing.', $stub);

        return str_replace(':field:', $field['name'], $stub);
    }

    private function textarea (array $field): string
    {
        $stub = isset($field['form']['richText'])
            ? File::get(self::TRIX_PATH)
            : File::get(self::TEXT_AREA_PATH);

        $stub = str_replace(':label:', $field['label'], $stub);

        return str_replace(':field:', $field['name'], $stub);
    }

    private function inputFile (array $field): string
    {
        $stub = File::get(self::INPUT_FILE_PATH);

        $stub = str_replace(':label:', $field['label'], $stub);

        return str_replace(':field:', 'new' . ucfirst($field['name']), $stub);
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

    private function inputDate (array $field): string
    {
        $stub = File::get(self::PICKADAY_PATH);
        $stub = str_replace(':label:', $field['label'], $stub);

        $stub = str_replace(':editing:', '', $stub);

        return str_replace(':field:', $field['name'], $stub);
    }

    private function inputBoolean (mixed $field): string
    {
        return $this->inputText($field);
    }
}
