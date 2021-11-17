<?php

namespace Dantofema\LaravelSetup\Services\Livewire;

use Dantofema\LaravelSetup\Facades\Field;

class NewFileService
{

    public function get (array $config, string $stub): string
    {
        $field = Field::config($config)->getFile();

        $stub = str_replace(
            ':editNewFile:',
            ! empty ($field) ? '$this->newFile = "";' : '',
            $stub
        );

        $stub = str_replace(
            ':createNewFile:',
            ! empty ($field) ? '$this->newFile = null;' : '',
            $stub
        );

        $replace = '';

        if ($field)
        {
            $name = $field['name'];
            $disk = $field['disk'];

            $replace = "\$this->setNewFile('$name', '$disk');";
        }

        $stub = str_replace(':saveNewFile:', $replace, $stub);

        return str_replace(
            ':newFile:',
            empty ($field) ? 'public $newFile;' : '',
            $stub
        );
    }
}