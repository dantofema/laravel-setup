<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

class NewFileService
{
    public function get (array $config, string $stub): string
    {
        $field = gen()->field()->getFile($config);

        $stub = str_replace(
            ':editNewFile:',
            ! empty($field) ? '$this->newFile = "";' : '',
            $stub
        );

        $stub = str_replace(
            ':createNewFile:',
            ! empty($field) ? '$this->newFile = null;' : '',
            $stub
        );

        $replace = '';

        if ($field)
        {
            $name = $field['name'];
            $disk = gen()->config()->disk($config);

            $replace = "\$this->setNewFile('$name', '$disk');";
        }

        $stub = str_replace(':saveNewFile:', $replace, $stub);

        return str_replace(
            ':newFile:',
            empty($field) ? '' : 'public $newFile;',
            $stub
        );
    }
}
