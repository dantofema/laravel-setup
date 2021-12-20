<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

use Dantofema\LaravelSetup\Services\FileSystemService;

class NewFileService
{
    public function get (array $config, string $stub): string
    {
        $fields = gen()->field()->getFiles($config);

        $newFiles = '';
        $createNewFiles = '';
        $editNewFiles = '';
        $saveNewFiles = '';
        $fileRules = "if (\$this->parameterAction == 'create') {" . PHP_EOL;

        foreach ($fields as $field)
        {
            (new FileSystemService())->execute($config);

            $newFiles .= 'public $new' . ucfirst($field['name']) . ';';
            $editNewFiles .= '$this->new' . ucfirst($field['name']) . ' = "";';

            $createNewFiles .= '$this->new' . ucfirst($field['name']) . ' = null;';

            $name = $field['name'];
            $disk = gen()->config()->disk($config);
            $saveNewFiles .= "\$this->setNewFile('$name', '$disk');";

            $fileRules .= "\$rules['new"
                . ucfirst($field['name']) . "'] = "
                . gen()->field()->getRulesToString($field) . ";" . PHP_EOL;
        }

        $stub = str_replace(':editNewFiles:', $editNewFiles, $stub);
        $stub = str_replace(':createNewFiles:', $createNewFiles, $stub);
        $stub = str_replace(':saveNewFiles:', $saveNewFiles, $stub);
        $stub = str_replace(':fileRules:', $fileRules . '}', $stub);
        return str_replace(':newFiles:', $newFiles, $stub);
    }
}
